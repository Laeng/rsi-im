<?php

use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('connect')->middleware(['web'])->name('connect.')->group(function () {
    Route::get('/', [AuthenticateController::class, 'signIn'])->name('sign-in');
    Route::post('/multi-factor', [AuthenticateController::class, 'multiFactor'])->name('multi-factor');
    Route::post('/captcha', [AuthenticateController::class, 'captcha'])->name('captcha');
    Route::post('/process', [AuthenticateController::class, 'process'])->name('process');
});


