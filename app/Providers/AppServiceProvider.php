<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if ($this->app->environment('production', 'staging')) {
        // Memaksa Laravel untuk menghasilkan URL aset menggunakan HTTPS
        URL::forceScheme('https'); 
        }
        if (env('VERCEL_ENV') === 'production' && env('APP_DEBUG') !== 'true') {
        // Abaikan variabel lingkungan dan set debug ke true
        config(['app.debug' => true]);
        }
    }
}
