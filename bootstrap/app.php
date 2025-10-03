<?php

use App\Http\Middleware\PreventBack;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\ValidateSession;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
        $middleware->alias([
            'prevent-back' => PreventBack::class,
            'security-headers' => SecurityHeaders::class,
            'validate-session' => ValidateSession::class
        ]);
        
        // Apply security headers globally
        $middleware->append(SecurityHeaders::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
