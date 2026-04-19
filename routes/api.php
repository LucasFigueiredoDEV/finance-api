<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransactionController;

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