<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\CategoryController;

/**
 * Endpoint to test if the API is running
 */
Route::get('/test', function () {
    return response()->json(['message' => 'API is running']);
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