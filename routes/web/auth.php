<?php

use App\Http\Controllers\Account\AuthenticateController;
use Illuminate\Support\Facades\Route;

Route::middleware('web')->group(function () {
    Route::get('connect', [AuthenticateController::class, 'login'])->name('login');
});
