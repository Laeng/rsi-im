<?php

use App\Http\Controllers\User\Api\ApiUserController;
use Illuminate\Support\Facades\Route;

Route::name('api.user.')->prefix('v1/user')->middleware(['auth:api', 'scope:profile'])->group(function () {
    Route::get('/', [ApiUserController::class, 'profile'])->name('profile');
    Route::get('/check', [ApiUserController::class, 'check'])->name('check');
});

Route::name('api.user.')->prefix('v1/user')->middleware(['auth:api', 'scope:joined-organizations'])->group(function () {
    Route::get('/organizations', [ApiUserController::class, 'organizations'])->name('organizations');
});

Route::name('api.user.')->prefix('v1/user')->middleware(['auth:api', 'scope:owned-games'])->group(function () {
    Route::get('/games', [ApiUserController::class, 'games'])->name('games');
});

Route::name('api.user.')->prefix('v1/user')->middleware(['auth:api', 'scope:launch-game'])->group(function () {
    Route::get('/launch', [ApiUserController::class, 'launch'])->name('launch');
});
