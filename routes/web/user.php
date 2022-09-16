<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserDeviceController;
use Illuminate\Support\Facades\Route;

Route::prefix('my')->middleware(['auth:web'])->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/account', [UserController::class, 'account'])->name('account');

    Route::prefix('device')->name('device.')->group(function () {
        Route::get('/', [UserDeviceController::class, 'device'])->name('index');
        Route::get('/data', [UserDeviceController::class, 'get'])->name('data');
        Route::delete('/data', [UserDeviceController::class, 'delete'])->name('data');
    });

    Route::get('/log', [UserController::class, 'log'])->name('log');

    Route::delete('/data', [UserController::class, 'delete'])->name('data');
});
