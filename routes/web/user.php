<?php

use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserDeviceController;
use App\Http\Controllers\User\UserLogController;
use Illuminate\Support\Facades\Route;

Route::prefix('my')->middleware(['auth:web'])->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/account', [UserController::class, 'account'])->name('account');

    Route::prefix('device')->name('device.')->group(function () {
        Route::get('/', [UserDeviceController::class, 'device'])->name('index');
        Route::get('/data', [UserDeviceController::class, 'get'])->name('data');
        Route::delete('/data', [UserDeviceController::class, 'delete'])->name('data');
    });

    Route::prefix('log')->name('log.')->group(function () {
        Route::get('/', [UserLogController::class, 'log'])->name('index');
        Route::get('/data', [UserLogController::class, 'get'])->name('data');
    });

    Route::delete('/data', [UserController::class, 'delete'])->name('data');
});
