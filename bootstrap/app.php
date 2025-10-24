<?php

use App\Http\Middleware\EnsureCompanyExists;
use App\Http\Middleware\EnsureCredentialsAreValid;
use App\Http\Middleware\EnsureTokenIsValid;
use App\Http\Middleware\EnsureUserIsActive;
use App\Http\Middleware\ValidationMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check_token' => EnsureTokenIsValid::class,
            'check_validation' => ValidationMiddleware::class,
            'check_credentials' => EnsureCredentialsAreValid::class,
            'check_active' => EnsureUserIsActive::class,
            'check_company' => EnsureCompanyExists::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
