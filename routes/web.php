<?php

use App\Http\Controllers\MailController;
use App\Http\Controllers\SignUpController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::view('signup', 'signup');
// Route::post('signup', [SignUpController::class, 'index']);



// Route::get('/send-mail', [MailController::class, 'index']);
