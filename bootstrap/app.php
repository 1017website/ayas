<?php

use App\Http\Middleware\EnsureDeveloper;
use App\Http\Middleware\EnsureRole;
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
        $middleware->alias([
            'developer' => EnsureDeveloper::class,
            'role' => EnsureRole::class,
        ]);
        $middleware->redirectGuestsTo(fn () => route('admin.login'));
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));
        $middleware->validateCsrfTokens(except: ['webhooks/qontak']);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
