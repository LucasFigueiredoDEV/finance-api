<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TransactionService;
use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Http\Resources\TransactionResource;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request,TransactionService $transactionService)
    {
        $transactions = $transactionService->paginate($request, auth()->id());
        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTransactionRequest $request, TransactionService $transactionService)
    {
        $transaction = $transactionService->create([
            ...$request->validated(),
            'user_id' => auth()->id(),
        ]);
        return (new TransactionResource($transaction))
                ->response()
                ->setStatusCode(201)
                ->header('Location', "/api/transactions/{$transaction->id}");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, TransactionService $transactionService)
    {
        $transaction = $transactionService->findOrFail($id, auth()->id());
        return new TransactionResource($transaction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTransactionRequest $request, string $id, TransactionService $service
    ) {
        $transaction = $service->findOrFail($id, auth()->id());

        $transaction = $service->update($transaction,$request->validated());

        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, TransactionService $service)
    {
        $deleted = $service->delete($id, auth()->id());

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
            $service->getSummary($request->all(), auth()->id())
        );
    }
}
