<?php

use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('user')->middleware(['auth:web'])->name('user.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
});
