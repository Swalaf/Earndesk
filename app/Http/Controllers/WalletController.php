<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WalletLedger;
use App\Models\User;
use App\Models\Referral;
use App\Services\SwiftKudiService;
use App\Services\RevenueAggregator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $earnDeskService;

    public function __construct(SwiftKudiService $earnDeskService)
    {
        $this->earnDeskService = $earnDeskService;
    }

    /**
     * Display wallet dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get or create wallet with error handling for missing columns
        try {
            $wallet = $user->wallet ?? Wallet::create([
                'user_id' => $user->id,
                'withdrawable_balance' => 0,
                'promo_credit_balance' => 0,
                'total_earned' => 0,
                'total_spent' => 0,
                'pending_balance' => 0,
                'escrow_balance' => 0,
            ]);
        } catch (\Exception $e) {
            Log::warning('Wallet creation failed, trying without earning categories', ['error' => $e->getMessage()]);
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'withdrawable_balance' => 0,
                    'promo_credit_balance' => 0,
                    'total_earned' => 0,
                    'total_spent' => 0,
                    'pending_balance' => 0,
                    'escrow_balance' => 0,
                ]
            );
        }

        // Get transactions
        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get recent transactions for the sidebar
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Get withdrawals
        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get ledger entries
        $ledgerEntries = WalletLedger::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate earnings stats
        $stats = [
            'total_earned' => $wallet->total_earned,
            'total_spent' => $wallet->total_spent,
            'pending_balance' => $wallet->pending_balance,
            'can_withdraw' => $user->canWithdraw(),
            'minimum_withdrawal' => User::getMinimumWithdrawal(),
        ];

        return view('wallet.index', compact(
            'wallet',
            'transactions',
            'recentTransactions',
            'withdrawals',
            'ledgerEntries',
            'stats'
        ));
    }

    /**
     * Display activation page
     */
    public function activate()
    {
        $user = Auth::user();
        
        try {
            $wallet = $user->wallet ?? Wallet::create([
                'user_id' => $user->id,
                'withdrawable_balance' => 0,
                'promo_credit_balance' => 0,
                'total_earned' => 0,
                'total_spent' => 0,
                'pending_balance' => 0,
                'escrow_balance' => 0,
            ]);
        } catch (\Exception $e) {
            // If wallet creation fails (e.g., missing columns), try without the new columns
            Log::warning('Wallet creation failed, trying without earning categories', ['error' => $e->getMessage()]);
            $wallet = Wallet::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'withdrawable_balance' => 0,
                    'promo_credit_balance' => 0,
                    'total_earned' => 0,
                    'total_spent' => 0,
                    'pending_balance' => 0,
                    'escrow_balance' => 0,
                ]
            );
        }

        $isActivated = $wallet->is_activated ?? false;
        $activationFee = User::getActivationFee();
        $referredActivationFee = User::getReferredActivationFee();

        // Check if user was referred. Prefer explicit referred_by relation but fall back to Referral records
        $referredBy = $user->referredBy;
        if (!$referredBy) {
            // First, try to find a referral record linked to this user by id or email
            $referralRecord = Referral::where('referred_user_id', $user->id)
                ->orWhere('referred_email', $user->email)
                ->first();

            // If not found, check for any session-stored referral code (in case user came via /ref/{code} but registration didn't persist it)
            if (!$referralRecord) {
                $sessionCode = session('referral_code');
                if ($sessionCode) {
                    $referralRecord = Referral::where('referral_code', $sessionCode)->first();
                }
            }

            if ($referralRecord) {
                $referredBy = $referralRecord->user;
            }
        }

        $actualFee = $referredBy ? $referredActivationFee : $activationFee;

        return view('wallet.activate', compact(
            'wallet',
            'isActivated',
            'activationFee',
            'referredActivationFee',
            'actualFee',
            'referredBy'
        ));
    }

    /**
     * Process activation
     */
    public function processActivation(Request $request)
    {
        $user = Auth::user();
        // Determine referrer: use relation first, then fallback to Referral record if present
        $referrer = $user->referredBy;
        if (!$referrer) {
            $referralRecord = Referral::where('referred_user_id', $user->id)
                ->orWhere('referred_email', $user->email)
                ->first();

            // also check session code as a last resort
            if (!$referralRecord) {
                $sessionCode = session('referral_code');
                if ($sessionCode) {
                    $referralRecord = Referral::where('referral_code', $sessionCode)->first();
                }
            }

            if ($referralRecord) {
                $referrer = $referralRecord->user;
            }
        }

        $result = $this->earnDeskService->activateUser($user, $referrer);

        if ($result['success']) {
            // Check if mandatory task creation gate is enabled
            $gateEnabled = \App\Models\SystemSetting::get('mandatory_task_creation_enabled', true);
            
            if ($gateEnabled) {
                // Redirect to start-your-journey page for new activation
                return redirect()->route('start-your-journey')
                    ->with('success', $result['message'] . ' Now create your first campaign to unlock earning!');
            }
            
            return redirect()->route('dashboard')
                ->with('success', $result['message']);
        }

        // If user needs to deposit, redirect to deposit page
        if (isset($result['needs_deposit']) && $result['needs_deposit']) {
            return redirect()->route('wallet.deposit')
                ->with('error', $result['message']);
        }

        return redirect()->route('wallet.activate')
            ->with('error', $result['message'])
            ->withInput();
    }

    /**
     * Display deposit form or process deposit
     */
    public function deposit(Request $request)
    {
        $user = Auth::user();
        
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

        // Check for required amount from task creation redirect
        $requiredAmount = $request->query('required');
        if ($requiredAmount) {
            session(['insufficient_balance_required' => $requiredAmount]);
        }

        // Handle GET request - show deposit form
        if ($request->isMethod('GET')) {
            return view('wallet.deposit', compact('wallet'));
        }

        // Handle POST request - process deposit
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        // For demo, simulate successful deposit
        $wallet->addWithdrawable($request->amount, 'deposit');

        // Create transaction
        Transaction::create([
            'wallet_id' => $wallet->id,
            'user_id' => $user->id,
            'type' => Transaction::TYPE_DEPOSIT,
            'amount' => $request->amount,
            'currency' => 'NGN',
            'status' => 'completed',
            'description' => 'Wallet deposit (Demo)',
            'reference' => Transaction::generateReference('DEP'),
        ]);

        // Create ledger entry
        WalletLedger::createEntry(
            $wallet,
            WalletLedger::TYPE_DEPOSIT,
            $request->amount,
            $wallet->withdrawable_balance - $request->amount,
            $wallet->withdrawable_balance,
            $wallet->promo_credit_balance,
            $wallet->promo_credit_balance,
            'Wallet deposit',
            'deposit',
            null
        );

        Log::info('Deposit processed', [
            'user_id' => $user->id,
            'amount' => $request->amount,
        ]);

        // Auto-aggregate revenue for today
        RevenueAggregator::aggregateForDate(now()->toDateString());

        // Check if user has a pending task creation (redirect after deposit success)
        $redirectRoute = session('deposit_success_redirect');
        
        if ($redirectRoute && $redirectRoute === route('tasks.create.resume')) {
            // Don't clear the task_creation_data here - the resume method needs it
            // Only clear the redirect flag
            session()->forget('deposit_success_redirect');
            
            return redirect($redirectRoute)
                ->with('success', '💰 Deposit of ₦' . number_format($request->amount, 2) . ' successful! Your form is ready to submit.');
        }

        return redirect()->route('wallet.index')
            ->with('success', 'Deposit of ₦' . number_format($request->amount, 2) . ' successful!');
    }

    /**
     * Display withdrawal form
     */
    public function withdraw()
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return redirect()->route('wallet.index')
                ->with('error', 'Wallet not found');
        }

        $minimumWithdrawal = User::getMinimumWithdrawal();
        $canWithdraw = $user->canWithdraw();

        return view('wallet.withdraw', compact(
            'wallet',
            'minimumWithdrawal',
            'canWithdraw'
        ));
    }

    /**
     * Process withdrawal request
     */
    public function processWithdrawal(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
            'method' => 'required|in:bank,usdt',
            'instant' => 'nullable|boolean',
        ]);

        $user = Auth::user();
        $amount = floatval($request->amount);
        $method = $request->method;
        $instant = $request->boolean('instant', false);

        $result = $this->earnDeskService->processWithdrawal($user, $amount, $method, $instant);

        if ($result['success']) {
            return redirect()->route('wallet.index')
                ->with('success', $result['message'] . ' Net amount: ₦' . number_format($result['net_amount'], 2));
        }

        return redirect()->route('wallet.withdraw')
            ->with('error', $result['message'])
            ->withInput();
    }

    /**
     * Get wallet balance (API)
     */
    public function balance(Request $request)
    {
        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return response()->json([
                'success' => false,
                'message' => 'Wallet not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'withdrawable' => $wallet->withdrawable_balance,
                'promo_credit' => $wallet->promo_credit_balance,
                'total' => $wallet->withdrawable_balance + $wallet->promo_credit_balance,
                'escrow' => $wallet->escrow_balance,
                'is_activated' => $wallet->is_activated,
                'formatted' => $wallet->getFormattedBalance(),
            ],
        ]);
    }

    /**
     * Get transaction history (API)
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type');
        $status = $request->get('status');

        $query = Transaction::where('user_id', $user->id);

        if ($type) {
            $query->where('type', $type);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $transactions = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $transactions,
        ]);
    }

    /**
     * Add promo credit (admin only - for bonuses, streaks, etc.)
     */
    public function addPromoCredit(Request $request)
    {
        // This would typically be admin-only
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'required|string',
        ]);

        $user = Auth::user();
        $wallet = $user->wallet;

        if (!$wallet) {
            return redirect()->route('wallet.index')
                ->with('error', 'Wallet not found');
        }

        $wallet->addPromoCredit($request->amount);

        WalletLedger::createEntry(
            $wallet,
            WalletLedger::TYPE_PROMO_CREDIT,
            $request->amount,
            $wallet->withdrawable_balance,
            $wallet->withdrawable_balance,
            $wallet->promo_credit_balance - $request->amount,
            $wallet->promo_credit_balance,
            $request->description,
            'bonus',
            null
        );

        return redirect()->route('wallet.index')
            ->with('success', '₦' . number_format($request->amount, 2) . ' promo credit added!');
    }

    /**
     * Display escrow transactions
     */
    public function escrow()
    {
        $user = auth()->user();
        
        // Get escrow transactions where user is payer or payee
        $escrowTransactions = \App\Models\EscrowTransaction::where('payer_id', $user->id)
            ->orWhere('payee_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('escrow.index', [
            'transactions' => $escrowTransactions,
        ]);
    }
}
