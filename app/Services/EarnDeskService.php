<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\WalletLedger;
use App\Models\Referral;
use App\Models\FraudLog;
use App\Models\Task;
use App\Models\TaskCompletion;
use App\Models\TaskBundle;
use App\Models\TaskCategory;
use App\Models\RevenueReport;
use App\Models\SystemSetting;
use App\Models\ActivationLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EarnDeskService
{
    /**
     * Get the default currency code from settings
     */
    public static function getCurrencyCode(): string
    {
        return SystemSetting::get('default_currency', 'NGN');
    }

    /**
     * Get the currency symbol for display
     */
    public static function getCurrencySymbol(): string
    {
        $code = self::getCurrencyCode();
        switch ($code) {
            case 'USD':
                return '$';
            case 'USDT':
                return '₮';
            case 'NGN':
            default:
                return '₦';
        }
    }

    /**
     * Format amount with currency symbol
     */
    public static function formatCurrency(float $amount): string
    {
        return self::getCurrencySymbol() . number_format($amount, 2);
    }

    /**
     * Activation fee constants
     */
    public const ACTIVATION_FEE = 1000;
    public const REFERRED_ACTIVATION_FEE = 2000;
    public const REFERRER_BONUS = 1000;
    public const PLATFORM_REVENUE = 1000;

    /**
     * Withdrawal constants
     */
    public const MIN_WITHDRAWAL = 3000;
    public const STANDARD_FEE_PERCENT = 5;
    public const INSTANT_FEE_PERCENT = 10;

    /**
     * Earnings split
     */
    public const WITHDRAWABLE_RATIO = 0.80;
    public const PROMO_CREDIT_RATIO = 0.20;

    /**
     * Task budget constants
     */
    public const MIN_TASK_BUNDLE_BUDGET = 2500;
    public const MIN_MACRO_TASK_BUDGET = 1000;
    public const PLATFORM_COMMISSION = 25;

    /**
     * Task type groups
     */
    public const TYPE_GROUP_MICRO = 'micro';
    public const TYPE_GROUP_UGC = 'ugc';
    public const TYPE_GROUP_REFERRAL = 'referral';
    public const TYPE_GROUP_PREMIUM = 'premium';

    /**
     * Record a transaction in the ledger
     *
     * @param User $user
     * @param string $type
     * @param float $amount
     * @param int|null $walletId
     * @param string|null $description
     * @return Transaction
     */
    public function recordTransaction(
        User $user,
        string $type,
        float $amount,
        ?int $walletId = null,
        ?string $description = null
    ): Transaction {
        return Transaction::create([
            'user_id' => $user->id,
            'wallet_id' => $walletId,
            'type' => $type,
            'amount' => $amount,
            'status' => 'completed',
            'description' => $description,
            'reference' => 'TXN-' . strtoupper(uniqid()),
        ]);
    }

    /**
     * Activate a user's account
     */
    public function activateUser(User $user, ?User $referrer = null): array
    {
        return DB::transaction(function () use ($user, $referrer) {
            // Get or create wallet
            $wallet = $user->wallet ?? Wallet::create([
                'user_id' => $user->id,
                'withdrawable_balance' => 0,
                'promo_credit_balance' => 0,
                'total_earned' => 0,
                'total_spent' => 0,
                'pending_balance' => 0,
                'escrow_balance' => 0,
            ]);

            // Check if already activated
            if ($wallet->is_activated) {
                return [
                    'success' => false,
                    'message' => 'Account is already activated',
                ];
            }

            // Prevent self-referral
            if ($referrer && $referrer->id === $user->id) {
                $referrer = null; // Ignore self-referral
                Log::warning('Self-referral detected and ignored', ['user_id' => $user->id]);
            }

            // Determine activation fee using SystemSetting (supports referred users discount)
            $isReferred = $referrer !== null;
            $activationFee = SystemSetting::getActivationFeeForUser($isReferred);
            $platformRevenue = SystemSetting::getNumber('activation_fee', 1000);

            // Check if user has enough balance
            $totalBalance = $wallet->withdrawable_balance + $wallet->promo_credit_balance;
            if ($totalBalance < $activationFee) {
                return [
                    'success' => false,
                    'message' => 'Insufficient balance. You need ' . self::formatCurrency($activationFee) . ' to activate. Please deposit funds first.',
                    'needs_deposit' => true,
                ];
            }

            // Deduct activation fee: use promo credit first, then withdrawable
            $remaining = $activationFee;
            if ($wallet->promo_credit_balance > 0) {
                $usePromo = min($wallet->promo_credit_balance, $remaining);
                $wallet->deductPromoCredit($usePromo, 'activation');
                $remaining -= $usePromo;
            }
            if ($remaining > 0) {
                $ok = $wallet->deductWithdrawable($remaining, 'activation');
                if (!$ok) {
                    return [
                        'success' => false,
                        'message' => 'Insufficient withdrawable balance to complete activation. Please deposit funds.',
                        'needs_deposit' => true,
                    ];
                }
            }

            // Mark wallet as activated
            $wallet->is_activated = true;
            $wallet->activated_at = now();
            $wallet->save();

            // Create activation log
            ActivationLog::create([
                'user_id' => $user->id,
                'activation_type' => $isReferred ? 'referral' : 'normal',
                'activation_fee' => $activationFee,
                'referral_bonus' => $isReferred ? self::REFERRER_BONUS : 0,
                'status' => 'completed',
                'reference' => 'ACT-' . strtoupper(uniqid()),
            ]);

            // Credit referrer if applicable
            if ($isReferred && $referrer) {
                $referrerBonus = SystemSetting::getNumber('referrer_bonus', self::REFERRER_BONUS);
                if ($referrer->wallet) {
                    $referrer->wallet->addWithdrawable($referrerBonus, 'referral_bonus', 'Referral bonus for activating ' . $user->name);
                }

                // Create referral record if not exists
                $referral = Referral::where('referrer_id', $referrer->id)
                    ->where('referred_user_id', $user->id)
                    ->first();
                if ($referral) {
                    $referral->reward_earned = ($referral->reward_earned ?? 0) + $referrerBonus;
                    $referral->is_activated = true;
                    $referral->save();
                }
            }

            return [
                'success' => true,
                'message' => 'Account activated successfully!',
                'data' => [
                    'wallet' => $wallet,
                    'activation_fee' => $activationFee,
                    'referral_bonus' => $isReferred ? SystemSetting::getNumber('referrer_bonus', self::REFERRER_BONUS) : 0,
                ],
            ];
        });
    }

    /**
     * Calculate withdrawal fee
     */
    public static function calculateWithdrawalFee(float $amount, bool $isInstant = false): float
    {
        $percent = $isInstant ? self::INSTANT_FEE_PERCENT : self::STANDARD_FEE_PERCENT;
        return ($amount * $percent) / 100;
    }

    /**
     * Calculate platform commission from task budget
     */
    public static function calculatePlatformCommission(float $budget): float
    {
        return ($budget * self::PLATFORM_COMMISSION) / 100;
    }

    /**
     * Calculate earnings split (withdrawable vs promo credit)
     */
    public static function calculateEarningsSplit(float $totalEarnings): array
    {
        return [
            'withdrawable' => $totalEarnings * self::WITHDRAWABLE_RATIO,
            'promo_credit' => $totalEarnings * self::PROMO_CREDIT_RATIO,
        ];
    }

    /**
     * Validate task budget meets minimum requirements
     */
    public static function validateTaskBudget(float $budget, string $taskType): array
    {
        switch ($taskType) {
            case 'micro':
                $minBudget = self::MIN_TASK_BUNDLE_BUDGET;
                break;
            case 'macro':
                $minBudget = self::MIN_MACRO_TASK_BUDGET;
                break;
            default:
                $minBudget = self::MIN_TASK_BUNDLE_BUDGET;
        }

        return [
            'valid' => $budget >= $minBudget,
            'minimum' => $minBudget,
            'message' => $budget >= $minBudget 
                ? 'Budget is valid' 
                : "Minimum budget for {$taskType} tasks is " . self::formatCurrency($minBudget),
        ];
    }
}
