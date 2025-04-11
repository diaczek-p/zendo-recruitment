<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('orders')->group(function () {
        Route::post('/simulate-update', [OrdersController::class, 'simulateUpdate']);
        Route::get('/', [OrdersController::class, 'index']);
    });
});
