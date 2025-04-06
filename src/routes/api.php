<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TravelOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('travel-orders', [TravelOrderController::class, 'store']);    
    Route::get('travel-orders', [TravelOrderController::class, 'index']);
    Route::get('travel-orders/search', [TravelOrderController::class, 'search']);
    Route::get('travel-orders/{id}', [TravelOrderController::class, 'show']);
    Route::patch('travel-orders/{id}/status', [TravelOrderController::class, 'updateStatus']);
    Route::patch('travel-orders/{id}/cancel', [TravelOrderController::class, 'cancel']);
});
