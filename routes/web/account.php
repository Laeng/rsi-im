<?php

use App\Http\Controllers\Account\AuthenticateController;
use App\Http\Controllers\Account\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('connect')->middleware(['web'])->name('connect.')->group(function () {
    Route::get('/', [AuthenticateController::class, 'signIn'])->name('sign-in');
    Route::post('/multi-factor', [AuthenticateController::class, 'multiFactor'])->name('multi-factor');
    Route::post('/captcha', [AuthenticateController::class, 'captcha'])->name('captcha');
    Route::post('/process', [AuthenticateController::class, 'process'])->name('process');
});

Route::prefix('user')->middleware(['auth:web'])->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
});
