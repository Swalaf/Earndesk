<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SystemSetting;
use Illuminate\Support\Facades\Config;
use App\Models\Transaction;
use App\Observers\TransactionObserver;
use App\Services\TaskCreationService;
use App\Repositories\TaskRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register TaskRepository as singleton
        $this->app->singleton(TaskRepository::class, function ($app) {
            return new TaskRepository();
        });

        // Register TaskCreationService
        $this->app->singleton(TaskCreationService::class, function ($app) {
            return new TaskCreationService(
                $app->make(TaskRepository::class),
                $app->make(\App\Services\EarnDeskService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Apply mail configuration from system settings if present
        try {
            $enabled = SystemSetting::getBool('smtp_enabled', false);

            if ($enabled) {
                $driver = SystemSetting::get('smtp_driver', config('mail.default'));
                $host = SystemSetting::get('smtp_host', config('mail.mailers.smtp.host'));
                $port = SystemSetting::getNumber('smtp_port', config('mail.mailers.smtp.port'));
                $username = SystemSetting::get('smtp_username', config('mail.mailers.smtp.username'));
                $password = SystemSetting::getDecrypted('smtp_password', config('mail.mailers.smtp.password'));
                $encryption = SystemSetting::get('smtp_encryption', config('mail.mailers.smtp.encryption'));
                $fromAddress = SystemSetting::get('smtp_from_email', config('mail.from.address'));
                $fromName = SystemSetting::get('smtp_from_name', config('mail.from.name'));

                Config::set('mail.default', $driver);
                Config::set('mail.mailers.smtp.host', $host);
                Config::set('mail.mailers.smtp.port', $port);
                Config::set('mail.mailers.smtp.username', $username);
                Config::set('mail.mailers.smtp.password', $password);
                Config::set('mail.mailers.smtp.encryption', $encryption === 'none' ? null : $encryption);
                Config::set('mail.from.address', $fromAddress);
                Config::set('mail.from.name', $fromName);

                // Rebind mailer to ensure runtime picks up new config
                if (app()->bound('mail.manager')) {
                    app()->forgetInstance('mail.manager');
                }
                if (app()->bound('mailer')) {
                    app()->forgetInstance('mailer');
                }
            }
        } catch (\Exception $e) {
            // Do not break app boot on errors related to system settings
        }

        // Register model observers (safe/no-op if registration fails)
        try {
            Transaction::observe(TransactionObserver::class);
        } catch (\Throwable $e) {
            // ignore observer registration issues
        }
    }
}
