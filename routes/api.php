<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/login', [AuthController::class, 'login'])
    ->middleware([
        'check_validation:login',
        'check_credentials',
        'check_active'
    ]);

Route::middleware(['check_token:signup_verification_token'])
    ->get('verify/{token}', [AuthController::class, 'verifyEmail']);


Route::middleware(['check_token:login_token'])->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);

    Route::prefix("/profile")->group(function () {
        Route::get('/', [AuthController::class, 'profile']);
        Route::post('/update', [AuthController::class, 'update'])->middleware([
            'check_validation:update_user'
        ]);
    });

    Route::prefix("/company")->group(function () {
        Route::post('/create', [CompanyController::class, 'create'])->middleware([
            'check_validation:company'
        ]);
        Route::post('/update', [CompanyController::class, 'update'])->middleware([
            'check_validation:company',
            'check_company'
        ]);
        Route::post('/delete', [CompanyController::class, 'delete'])->middleware([
            'check_company'
        ]);
        Route::get('/read', [CompanyController::class, 'read'])->withTrashed();

        Route::prefix("/member")->group(function () {
            Route::post('/assign', [CompanyController::class, 'assignMember'])->middleware([
                'check_validation:member'
            ]);
            Route::post('/remove', [CompanyController::class, 'removeMember'])->middleware([
                'check_validation:member'
            ]);
        });
    });
});





























// Route::prefix("/company")->group(function () {
//     Route::post('/create', [CompanyController::class, 'create']);
//     Route::middleware(['check_company'])->group(function () {
//         Route::post('/update', [CompanyController::class, 'update']);
//         Route::post('/delete', [CompanyController::class, 'delete']);
//     });
// });
