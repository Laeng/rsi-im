<?php

use App\Http\Controllers\Account\API\UserController;
use Illuminate\Support\Facades\Route;

Route::name('api.user.')->prefix('v1/user')->middleware(['auth:api', 'scope:basic-info'])->group(function () {
    Route::get('data', [UserController::class, 'data'])->name('data');
});

