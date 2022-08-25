<?php

use App\Http\Controllers\Account\API\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.user.')->prefix('v1/user')->middleware(['auth:api', 'scope:basic-info'])->group(function () {
    Route::post('data', [UserController::class, 'data'])->name('data');
});

