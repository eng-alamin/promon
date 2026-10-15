<?php

use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\EnsureUserIsSuperAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Guests who open a protected page go to the admin login (there is no route named "login").
        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        // Logged-in users who open the login page go straight to the dashboard.
        $middleware->redirectUsersTo(fn () => route('admin.dashboard'));

        $middleware->alias([
            'active' => EnsureUserIsActive::class,
            'super_admin' => EnsureUserIsSuperAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
