<?php

use App\Http\Controllers\OAuth2\AuthorizationController;
use App\Http\Controllers\OAuth2\TransientTokenController;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\ApproveAuthorizationController;
use Laravel\Passport\Http\Controllers\DenyAuthorizationController;

Route::name('oauth2.authorization.')->prefix('oauth2')->middleware('auth:web')->group(function () {
    Route::get('authorize', [AuthorizationController::class, 'authorize'])->name('authorize');
    Route::post('authorize', [ApproveAuthorizationController::class, 'approve'])->name('approve');
    Route::delete('authorize', [DenyAuthorizationController::class, 'deny'])->name('deny');
});

Route::name('oauth2.token.')->prefix('oauth2')->withoutMiddleware([VerifyCsrfToken::class])->group(function () {
    Route::post('token', [AccessTokenController::class, 'issueToken'])->middleware(['throttle'])->name('issue');
    Route::post('token/refresh', [TransientTokenController::class, 'refresh'])->middleware('auth:web')->name('refresh');
});
