<?php

use App\Http\Middleware\CheckCompany;
use App\Http\Middleware\CheckCredentials;
use App\Http\Middleware\CheckToken;
use App\Http\Middleware\CheckActive;
use App\Http\Middleware\CheckChannel;
use App\Http\Middleware\CheckValidation;
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
            'check_token' => CheckToken::class,
            'check_validation' => CheckValidation::class,
            'check_credentials' => CheckCredentials::class,
            'check_active' => CheckActive::class,
            'check_company' => CheckCompany::class,
            'check_channel' => CheckChannel::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
