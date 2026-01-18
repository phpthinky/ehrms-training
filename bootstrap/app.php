<?php

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
        // Register middleware aliases
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'status' => \App\Http\Middleware\CheckStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Custom handling for unauthenticated users
        $exceptions->respond(function ($response, $exception, $request) {
            if ($exception instanceof \Illuminate\Auth\AuthenticationException) {
                // Check if it's an AJAX request
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'Unauthenticated.'], 401);
                }
                
                // For web requests, redirect to login without session expired message
                return redirect()->guest(route('login'));
            }
            
            return $response;
        });
    })->create();
