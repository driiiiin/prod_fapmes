<?php

namespace App\Providers;

use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\RateLimiter as FacadesRateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Cache\RateLimiting\Limit;

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
    // Force HTTPS only in production, ensure HTTP for local development
    if ($this->app->environment('production')) {
        $this->app['request']->server->set('HTTPS', 'on');
        URL::forceScheme('https');
    } else {
        // Ensure HTTP for local development
        URL::forceScheme('http');
    }

    RateLimiter::for('login', function (Request $request) {
        return Limit::perMinute(4)->by($request->input('login'))->response(
            static function () {
                return response()->json(['message' => 'Too many login attempts. Please try again after 24 hours.'], 429);
            }
        );
    });
}
}

