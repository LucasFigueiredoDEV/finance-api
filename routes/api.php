<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\AuthController;

/**
 * Endpoint to test if the API is running
 */
Route::get('/test', function () {
    return response()->json(['message' => 'API is running']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
    });

    Route::prefix('transactions')->group(function () {
        Route::get('/summary', [TransactionController::class, 'summary']);
    });

    /**
     * Transaction routes
     */
    Route::apiResource('transactions', TransactionController::class);

    /**
     * Category routes
     */
    Route::apiResource('categories', CategoryController::class);
});