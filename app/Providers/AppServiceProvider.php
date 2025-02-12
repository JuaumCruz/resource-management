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
        if(app()->environment('production')) {
            URL::forceScheme('https');
        }

        response()->macro('withSecurityHeaders', function ($response) {
            return $response->withHeaders([
                'X-XSS-Protection' => '1; mode=block',
                'X-Frame-Options' => 'SAMEORIGIN',
                'X-Content-Type-Options' => 'nosniff',
                'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains'
            ]);
        });
    }
}
