<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\CheckRoleApi;
use App\Http\Middleware\EnsureUserIsAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin/*') || $request->routeIs('admin.*')) {
                return route('admin.login'); // redirect ke admin login page
            }
            // Default redirect untuk selain admin
            return route('login');
        });
        $middleware->alias([
            'auth.sanctum.api' => EnsureUserIsAuthenticated::class,
            'role' => CheckRole::class,
            'checkRoleApi' => CheckRoleApi::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
