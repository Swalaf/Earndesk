<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SystemSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
        'description',
    ];

    protected $casts = [
        'type' => 'string',
    ];

    // Setting groups
    const GROUP_GENERAL = 'general';
    const GROUP_SMTP = 'smtp';
    const GROUP_PAYMENT = 'payment';
    const GROUP_SECURITY = 'security';
    const GROUP_CRON = 'cron';
    const GROUP_CURRENCY = 'currency';
    const GROUP_NOTIFICATION = 'notification';
    const GROUP_MAINTENANCE = 'maintenance';
    const GROUP_REGISTRATION = 'registration';
    const GROUP_COMMISSION = 'commission';
    const GROUP_EMAIL_TEMPLATES = 'email_templates';

    const GROUPS = [
        self::GROUP_GENERAL => 'General',
        self::GROUP_SMTP => 'Email/SMTP',
        self::GROUP_EMAIL_TEMPLATES => 'Email Templates',
        self::GROUP_PAYMENT => 'Payment Gateways',
        self::GROUP_SECURITY => 'Security',
        self::GROUP_CRON => 'Cron Jobs',
        self::GROUP_CURRENCY => 'Currency',
        self::GROUP_NOTIFICATION => 'Notifications',
        self::GROUP_MAINTENANCE => 'Maintenance',
        self::GROUP_REGISTRATION => 'Registration',
        self::GROUP_COMMISSION => 'Commission & Earnings',
        'task-gate' => 'Task Creation Gate',
    ];

    // Encryption for sensitive values
    private const ENCRYPTION_METHOD = 'AES-256-CBC';

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            // Clear the specific setting cache
            Cache::forget('setting.' . $setting->key);
            // Also clear the all-settings cache if it exists
            Cache::forget('system_settings');
        });

        static::deleted(function ($setting) {
            // Clear the specific setting cache
            Cache::forget('setting.' . $setting->key);
            // Also clear the all-settings cache if it exists
            Cache::forget('system_settings');
        });
    }

    /**
     * Scope: By group
     */
    public function scopeByGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Get a setting by key
     */
    public static function get(string $key, $default = null)
    {
        $setting = Cache::remember("setting.{$key}", 3600, function () use ($key) {
            return self::where('key', $key)->first();
        });

        if (!$setting) {
            return $default;
        }

        return self::castValue($setting->value, $setting->type);
    }

    /**
     * Check if a setting key exists
     */
    public static function keyExists(string $key): bool
    {
        return self::where('key', $key)->exists();
    }

    /**
     * Set a setting value (preserve encryption when type is 'encrypted')
     */
    public static function set(string $key, $value, string $group = 'general', string $type = 'text'): self
    {
        $setting = self::firstOrNew(['key' => $key]);
        
        // Store old value before update for audit log
        $oldValue = $setting->exists ? $setting->value : null;

        // Encrypt sensitive values
        if ($type === 'encrypted' && !empty($value)) {
            $value = Crypt::encryptString($value);
        }

        // If encrypted type but value empty, preserve existing encrypted value
        if ($type === 'encrypted' && empty($value) && $setting->exists) {
            $value = $setting->value;
        }

        $setting->value = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;
        $setting->group = $group;
        $setting->type = $type;
        $setting->save();

        Cache::forget("setting.{$key}");
        Cache::forget('system_settings');

        // Log the change with old and new values
        self::logChange($key, $oldValue, $value, $group);

        return $setting;
    }

    /**
     * Log setting change with old and new values
     */
    protected static function logChange(string $key, $oldValue, $newValue, string $group)
    {
        if (auth()->check()) {
            // Mask sensitive values in logs
            $maskedOldValue = self::maskSensitiveValue($oldValue);
            $maskedNewValue = self::maskSensitiveValue($newValue);
            
            SettingsAuditLog::create([
                'admin_id' => auth()->id(),
                'setting_key' => $key,
                'old_value' => $maskedOldValue,
                'new_value' => $maskedNewValue,
                'group' => $group,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        }
    }

    /**
     * Mask sensitive values for audit logs
     */
    protected static function maskSensitiveValue($value): ?string
    {
        if (empty($value)) {
            return null;
        }
        
        // Don't mask if it's already encrypted/encoded
        if (strlen($value) > 50 && preg_match('/^[a-zA-Z0-9+\/=]+$/', $value)) {
            return '[ENCRYPTED]';
        }
        
        return $value;
    }

    /**
     * Get decrypted value for encrypted settings
     */
    public static function getDecrypted(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();

        if (!$setting || $setting->type !== 'encrypted') {
            return $default;
        }

        try {
            return Crypt::decryptString($setting->value);
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Get boolean setting
     */
    public static function getBool(string $key, bool $default = false): bool
    {
        $value = self::get($key, $default);
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Get numeric setting
     */
    public static function getNumber(string $key, $default = 0)
    {
        $value = self::get($key, $default);
        return is_numeric($value) ? (float) $value : $default;
    }

    /**
     * Get array/json setting
     */
    public static function getArray(string $key, array $default = []): array
    {
        $value = self::get($key);
        if (is_string($value)) {
            return json_decode($value, true) ?? $default;
        }
        return is_array($value) ? $value : $default;
    }

    /**
     * Cast value to proper type
     */
    protected static function castValue($value, string $type)
    {
        switch ($type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'number':
            case 'decimal':
            case 'integer':
                return is_numeric($value) ? (strpos($value, '.') !== false ? (float) $value : (int) $value) : $value;
            case 'json':
                return json_decode($value, true);
            case 'array':
                return json_decode($value, true) ?? [];
            case 'encrypted':
                try {
                    return Crypt::decryptString($value);
                } catch (\Exception $e) {
                    return $value;
                }
            default:
                return $value;
        }
    }

    /**
     * Get minimum required budget for task creation
     */
    public static function getMinimumRequiredBudget(): float
    {
        return self::getNumber('minimum_required_budget', 2500);
    }

    /**
     * Check if mandatory task creation is enabled
     */
    public static function isMandatoryTaskCreationEnabled(): bool
    {
        return self::getBool('mandatory_task_creation_enabled', true);
    }

    /**
     * Get platform commission percentage
     */
    public static function getPlatformCommission(): float
    {
        return self::getNumber('platform_commission', 25);
    }

    /**
     * Get earnings split (withdrawable percentage)
     */
    public static function getWithdrawableEarningsSplit(): float
    {
        return self::getNumber('withdrawable_split', 80);
    }

    /**
     * Get minimum withdrawal amount
     */
    public static function getMinimumWithdrawal(): float
    {
        return self::getNumber('minimum_withdrawal', 1000);
    }

    /**
     * Get withdrawal fee percentage
     */
    public static function getWithdrawalFee(): float
    {
        return self::getNumber('withdrawal_fee', 0);
    }

    /**
     * Check if user registration is enabled
     */
    public static function isRegistrationEnabled(): bool
    {
        return self::getBool('registration_enabled', true);
    }

    /**
     * Check if compulsory activation fee is enabled
     */
    public static function isCompulsoryActivationFee(): bool
    {
        return self::getBool('compulsory_activation_fee', true);
    }

    /**
     * Get activation fee for a user (considering if referred)
     */
    public static function getActivationFeeForUser(bool $isReferred = false): float
    {
        $baseFee = self::getNumber('activation_fee', 1000);
        
        if ($isReferred) {
            $multiplier = self::getNumber('referred_activation_multiplier', 1.0);
            $discount = self::getNumber('referred_activation_discount', 0);
            
            // Apply multiplier first, then subtract discount
            $fee = ($baseFee * $multiplier) - $discount;
            return max(0, $fee); // Ensure fee doesn't go negative
        }
        
        return $baseFee;
    }

    /**
     * Check if email verification is required
     */
    public static function isEmailVerificationRequired(): bool
    {
        return self::getBool('email_verification_required', true);
    }

    /**
     * Check if referral system is enabled
     */
    public static function isReferralEnabled(): bool
    {
        return self::getBool('referral_enabled', true);
    }

    /**
     * Get activation fee
     */
    public static function getActivationFee(): float
    {
        return self::getNumber('activation_fee', 1000);
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceModeEnabled(): bool
    {
        return Cache::remember('maintenance_mode', 60, function () {
            return \DB::table('maintenance_mode')->first()->is_enabled ?? false;
        });
    }

    /**
     * Enable maintenance mode
     */
    public static function enableMaintenanceMode(string $message = '', int $adminId = null): void
    {
        \DB::table('maintenance_mode')->updateOrInsert(
            ['id' => 1],
            [
                'is_enabled' => true,
                'message' => $message,
                'enabled_at' => now(),
                'enabled_by' => $adminId ?? auth()->id(),
            ]
        );
        Cache::forget('maintenance_mode');
    }

    /**
     * Disable maintenance mode
     */
    public static function disableMaintenanceMode(): void
    {
        \DB::table('maintenance_mode')->where('id', 1)->update([
            'is_enabled' => false,
            'enabled_at' => null,
            'enabled_by' => null,
        ]);
        Cache::forget('maintenance_mode');
    }

    /**
     * Get all settings by group
     */
    public static function getByGroup(string $group): array
    {
        return Cache::remember("settings_group.{$group}", 3600, function () use ($group) {
            $settings = self::where('group', $group)->get();
            $result = [];
            foreach ($settings as $setting) {
                $result[$setting->key] = self::castValue($setting->value, $setting->type);
            }
            return $result;
        });
    }

    /**
     * Initialize default settings
     */
    public static function initializeDefaults(): void
    {
        $defaults = [
            // General
            'site_name' => ['value' => 'EarnDesk', 'group' => self::GROUP_GENERAL, 'type' => 'text'],
            'site_url' => ['value' => url('/'), 'group' => self::GROUP_GENERAL, 'type' => 'text'],
            'site_logo' => ['value' => '', 'group' => self::GROUP_GENERAL, 'type' => 'text'],

            // Registration
            'registration_enabled' => ['value' => true, 'group' => self::GROUP_REGISTRATION, 'type' => 'boolean'],
            'email_verification_required' => ['value' => true, 'group' => self::GROUP_REGISTRATION, 'type' => 'boolean'],
            'admin_approval_required' => ['value' => false, 'group' => self::GROUP_REGISTRATION, 'type' => 'boolean'],
            'referral_enabled' => ['value' => true, 'group' => self::GROUP_REGISTRATION, 'type' => 'boolean'],
            'activation_fee' => ['value' => 1000, 'group' => self::GROUP_REGISTRATION, 'type' => 'number'],
            'referred_activation_multiplier' => ['value' => 1.0, 'group' => self::GROUP_REGISTRATION, 'type' => 'number'],
            'referred_activation_discount' => ['value' => 0, 'group' => self::GROUP_REGISTRATION, 'type' => 'number'],
            'compulsory_activation_fee' => ['value' => true, 'group' => self::GROUP_REGISTRATION, 'type' => 'boolean'],

            // Commission
            'platform_commission' => ['value' => 25, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],
            'withdrawable_split' => ['value' => 80, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],
            'promo_credit_split' => ['value' => 20, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],
            'minimum_withdrawal' => ['value' => 1000, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],
            'withdrawal_fee_standard' => ['value' => 5, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],
            'withdrawal_fee_instant' => ['value' => 10, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],
            'minimum_required_budget' => ['value' => 2500, 'group' => self::GROUP_COMMISSION, 'type' => 'number'],

            // Currency
            'currency_ngn_enabled' => ['value' => true, 'group' => self::GROUP_CURRENCY, 'type' => 'boolean'],
            'currency_usd_enabled' => ['value' => false, 'group' => self::GROUP_CURRENCY, 'type' => 'boolean'],
            'currency_usdt_enabled' => ['value' => false, 'group' => self::GROUP_CURRENCY, 'type' => 'boolean'],
            'default_currency' => ['value' => 'NGN', 'group' => self::GROUP_CURRENCY, 'type' => 'text'],
            'auto_fetch_rates' => ['value' => false, 'group' => self::GROUP_CURRENCY, 'type' => 'boolean'],
            'ngn_to_usd_rate' => ['value' => 1500, 'group' => self::GROUP_CURRENCY, 'type' => 'number'],

            // Security
            'ip_tracking_enabled' => ['value' => true, 'group' => self::GROUP_SECURITY, 'type' => 'boolean'],
            'device_fingerprinting_enabled' => ['value' => true, 'group' => self::GROUP_SECURITY, 'type' => 'boolean'],
            'max_accounts_per_ip' => ['value' => 3, 'group' => self::GROUP_SECURITY, 'type' => 'number'],
            'rate_limiting_enabled' => ['value' => true, 'group' => self::GROUP_SECURITY, 'type' => 'boolean'],
            'self_task_prevention' => ['value' => true, 'group' => self::GROUP_SECURITY, 'type' => 'boolean'],
            'fraud_auto_flagging' => ['value' => true, 'group' => self::GROUP_SECURITY, 'type' => 'boolean'],

            // Cron Jobs
            'cron_task_expiry_enabled' => ['value' => true, 'group' => self::GROUP_CRON, 'type' => 'boolean'],
            'cron_referral_bonus_enabled' => ['value' => true, 'group' => self::GROUP_CRON, 'type' => 'boolean'],
            'cron_daily_streak_enabled' => ['value' => true, 'group' => self::GROUP_CRON, 'type' => 'boolean'],
            'cron_fraud_scan_enabled' => ['value' => true, 'group' => self::GROUP_CRON, 'type' => 'boolean'],

            // SMTP
            'smtp_driver' => ['value' => 'smtp', 'group' => self::GROUP_SMTP, 'type' => 'text'],
            'smtp_host' => ['value' => '', 'group' => self::GROUP_SMTP, 'type' => 'text'],
            'smtp_port' => ['value' => 587, 'group' => self::GROUP_SMTP, 'type' => 'number'],
            'smtp_username' => ['value' => '', 'group' => self::GROUP_SMTP, 'type' => 'text'],
            'smtp_password' => ['value' => '', 'group' => self::GROUP_SMTP, 'type' => 'encrypted'],
            'smtp_encryption' => ['value' => 'tls', 'group' => self::GROUP_SMTP, 'type' => 'text'],
            'smtp_from_email' => ['value' => '', 'group' => self::GROUP_SMTP, 'type' => 'text'],
            'smtp_from_name' => ['value' => 'EarnDesk', 'group' => self::GROUP_SMTP, 'type' => 'text'],
            'smtp_enabled' => ['value' => false, 'group' => self::GROUP_SMTP, 'type' => 'boolean'],

            // Paystack
            'paystack_public_key' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'text'],
            'paystack_secret_key' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'encrypted'],
            'paystack_sandbox' => ['value' => true, 'group' => self::GROUP_PAYMENT, 'type' => 'boolean'],
            'paystack_enabled' => ['value' => false, 'group' => self::GROUP_PAYMENT, 'type' => 'boolean'],

            // Kora
            'kora_public_key' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'text'],
            'kora_secret_key' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'encrypted'],
            'kora_sandbox' => ['value' => true, 'group' => self::GROUP_PAYMENT, 'type' => 'boolean'],
            'kora_enabled' => ['value' => false, 'group' => self::GROUP_PAYMENT, 'type' => 'boolean'],

            // Stripe
            'stripe_publishable_key' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'text'],
            'stripe_secret_key' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'encrypted'],
            'stripe_webhook_secret' => ['value' => '', 'group' => self::GROUP_PAYMENT, 'type' => 'encrypted'],
            'stripe_sandbox' => ['value' => true, 'group' => self::GROUP_PAYMENT, 'type' => 'boolean'],
            'stripe_enabled' => ['value' => false, 'group' => self::GROUP_PAYMENT, 'type' => 'boolean'],

            // Notifications
            'notify_task_approval' => ['value' => true, 'group' => self::GROUP_NOTIFICATION, 'type' => 'boolean'],
            'notify_task_rejection' => ['value' => true, 'group' => self::GROUP_NOTIFICATION, 'type' => 'boolean'],
            'notify_task_bundle' => ['value' => true, 'group' => self::GROUP_NOTIFICATION, 'type' => 'boolean'],
            'notify_referral_bonus' => ['value' => true, 'group' => self::GROUP_NOTIFICATION, 'type' => 'boolean'],
            'notify_withdrawal' => ['value' => true, 'group' => self::GROUP_NOTIFICATION, 'type' => 'boolean'],

            // Email Templates
            'email_welcome_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_welcome_subject' => ['value' => 'Welcome to EarnDesk!', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_task_approved_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_task_approved_subject' => ['value' => 'Your Task has been Approved!', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_task_rejected_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_task_rejected_subject' => ['value' => 'Your Task was Rejected', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_task_available_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_task_available_subject' => ['value' => 'New Tasks Available!', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_earnings_unlocked_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_earnings_unlocked_subject' => ['value' => 'Your Earnings are Now Available!', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_activation_reminder_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_activation_reminder_subject' => ['value' => 'Complete Your Activation', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_withdrawal_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_withdrawal_subject' => ['value' => 'Withdrawal Request Processed', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_referral_bonus_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_referral_bonus_subject' => ['value' => 'You Earned a Referral Bonus!', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
            'email_deposit_enabled' => ['value' => true, 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'boolean'],
            'email_deposit_subject' => ['value' => 'Deposit Confirmed', 'group' => self::GROUP_EMAIL_TEMPLATES, 'type' => 'text'],
        ];

        foreach ($defaults as $key => $config) {
            if (!self::where('key', $key)->exists()) {
                self::create([
                    'key' => $key,
                    'value' => is_bool($config['value']) ? ($config['value'] ? 'true' : 'false') : $config['value'],
                    'group' => $config['group'],
                    'type' => $config['type'],
                ]);
            }
        }
    }
}
