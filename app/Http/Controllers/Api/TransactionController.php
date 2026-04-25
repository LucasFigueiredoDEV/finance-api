<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,TransactionService $transactionService)
    {
        $transactions = $transactionService->paginate($request);
        return response()->json($transactions, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request, TransactionService $transactionService)
    {
        $transaction = $transactionService->create($request->validated());
        return response()
                ->json($transaction, 201)
                ->header('Location', "/api/transactions/{$transaction->id}");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TransactionService $transactionService)
    {
        $transaction = $transactionService->find($id);
        return response()->json($transaction, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, Transaction $transaction, TransactionService $service)
    {
        $transaction = $service->update($transaction, $request->validated());

        return response()->json($transaction, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TransactionService $service)
    {
        $deleted = $service->delete($id);

        if (! $deleted) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        return response()->json(['message' => 'Transaction deleted successfully'], 200);
    }

    /**
     * Summary of transactions
     */
    public function summary(Request $request, TransactionService $service)
    {
        return response()->json(
            $service->getSummary($request->all())
        );
    }
}
