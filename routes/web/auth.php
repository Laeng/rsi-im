<?php

use App\Http\Controllers\Account\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('connect', [AuthenticateController::class, 'login'])->name('login');
    Route::post('connect/submit', [AuthenticateController::class, 'loginSubmit'])->name('login.submit');
    Route::post('connect/multi-factor', [AuthenticateController::class, 'loginMultiFactor'])->name('login.multi-factor');
    Route::post('connect/captcha', [AuthenticateController::class, 'loginCaptcha'])->name('login.captcha');

});
