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
        // Force HTTPS ketika menggunakan ngrok
        // if ($this->app->environment('local') && request()->server('HTTP_X_FORWARDED_PROTO') === 'https') {
        //     URL::forceScheme('https');
        // }
        
        // // Atau force untuk semua request ngrok
        // if (str_contains(request()->getHost(), 'ngrok-free.app') || str_contains(request()->getHost(), 'ngrok.io')) {
        //     URL::forceScheme('https');
        // }
        if (app()->environment('production')) {
            URL::forceScheme('https');     // semua URL jadi https
            URL::forceRootUrl(config('app.url')); // opsional, ikut APP_URL
        }
    }
}
