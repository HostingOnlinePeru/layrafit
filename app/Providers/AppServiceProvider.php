<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            URL::forceScheme('https');
        } elseif (isset($_SERVER['HTTP_HOST']) && (str_contains($_SERVER['HTTP_HOST'], 'trycloudflare.com') || str_contains($_SERVER['HTTP_HOST'], 'loca.lt') || str_contains($_SERVER['HTTP_HOST'], 'serveousercontent.com'))) {
            URL::forceScheme('https');
        }

        // Compartir información del Tenant activo en todas las vistas
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $user = auth()->user();
            if ($user && $user->tenant) {
                $tenant = $user->tenant;
            } else {
                $tenant = \App\Models\Tenant::first();
            }

            $view->with('currentTenant', $tenant);
        });
    }
}
