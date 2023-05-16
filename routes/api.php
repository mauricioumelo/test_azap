<?php

use App\Http\Controllers\Api\V1\BalancoController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::prefix('balanco')->controller(BalancoController::class)->group(function () {
        Route::get('/', 'groupInvoices');
    });
});
