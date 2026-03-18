<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use App\Models\MailConfig;
use App\Models\SettingAdmin;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $setting = SettingAdmin::first();
        View::share('setting', $setting);

        $this->configureMailFromDatabase();
    }

    private function configureMailFromDatabase(): void
    {
        try {
            if (!Schema::hasTable('mail_configs')) {
                return;
            }

            $mailConfig = MailConfig::first();

            if (!$mailConfig) {
                return;
            }

            Config::set('mail.default', $mailConfig->MAIL_MAILER ?? 'smtp');
            Config::set('mail.mailers.smtp.host', $mailConfig->MAIL_HOST);
            Config::set('mail.mailers.smtp.port', (int) $mailConfig->MAIL_PORT);
            Config::set('mail.mailers.smtp.username', $mailConfig->MAIL_USERNAME);
            Config::set('mail.mailers.smtp.password', $mailConfig->MAIL_PASSWORD);
            Config::set('mail.mailers.smtp.encryption', $mailConfig->MAIL_ENCRYPTION);
            Config::set('mail.from.address', $mailConfig->MAIL_FROM_ADDRESS);
            Config::set('mail.from.name', config('app.name'));
        } catch (\Exception $e) {
            Log::error('Failed to load mail config from DB: ' . $e->getMessage());
        }
    }
}
