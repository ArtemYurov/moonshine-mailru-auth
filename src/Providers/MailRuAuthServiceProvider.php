<?php

declare(strict_types=1);

namespace ArtemYurov\MailRuAuth\Providers;

use Illuminate\Support\ServiceProvider;

final class MailRuAuthServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/mailru-auth.php', 'mailru-auth');
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'mailru-auth');

        if (config('mailru-auth.register_routes', true)) {
            $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        }

        $this->publishes([
            __DIR__ . '/../../config/mailru-auth.php' => config_path('mailru-auth.php'),
        ], 'mailru-auth-config');

        $this->publishes([
            __DIR__ . '/../../resources/views' => resource_path('views/vendor/mailru-auth'),
        ], 'mailru-auth-views');
    }
}
