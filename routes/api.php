<?php

use App\Http\Controllers\Api\V1\SendersController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('senders')->controller(SendersController::class)->group(function () {
        Route::get('/', 'index');
    });
});
