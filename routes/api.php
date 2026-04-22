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

/**
 * Transaction routes
 */
Route::apiResource('transactions', TransactionController::class);

/**
 * Category routes
 */
Route::apiResource('categories', CategoryController::class);