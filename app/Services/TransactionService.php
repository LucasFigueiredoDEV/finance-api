<?php
namespace App\Services;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TransactionService {

    public function paginate(Request $request, int $userId) : LengthAwarePaginator {
        return Transaction::query()
            ->where('user_id', $userId)
            ->when($request['type'] ?? null, fn($q, $type) => $q->type($type))
            ->when(
                $request['start_date'] ?? null || $request['end_date'] ?? null,
                fn($q) => $q->dateBetween(
                    $request['start_date'] ?? null,
                    $request['end_date'] ?? null
                )
            )
            ->when($request['description'] ?? null, fn($q, $term) => $q->search($term))
            ->when(
                $request['min_amount'] ?? null || $request['max_amount'] ?? null,
                fn($q) => $q->amountBetween(
                    $request['min_amount'] ?? null,
                    $request['max_amount'] ?? null
                )
            )
            ->with('category')
            ->paginate(10);
    }

    public function create(array $data) : Transaction {
        return Transaction::create($data);
    }

    public function findOrFail(
        string $id,
        int $userId
    ): Transaction {
        return Transaction::query()
            ->with('category')
            ->where('user_id', $userId)
            ->findOrFail($id);
    }

    public function update(Transaction $transaction, array $data) : Transaction {
        $transaction->update($data);
        return $transaction->fresh(['category']);
    }

    public function delete(string $id, int $userId) : bool {
        return Transaction::where('user_id', $userId)->where('id', $id)->delete() > 0;
    }

    public function getSummary(array $filters = [], int $userId) {
        $result = Transaction::query()
                    ->selectRaw("
                        COALESCE(SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END), 0) as income,
                        COALESCE(SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END), 0) as expense
                    ")
                    ->where('user_id', $userId)
                    ->when($filters['start_date'] ?? null, fn($q, $date) =>
                        $q->whereDate('date', '>=', $date)
                    )
                    ->when($filters['end_date'] ?? null, fn($q, $date) =>
                        $q->whereDate('date', '<=', $date)
                    )
                    ->first();
        return [
            'income'  => (float) $result->income,
            'expense' => (float) $result->expense,
            'balance' => (float) ($result->income - $result->expense),
        ];
    }
}