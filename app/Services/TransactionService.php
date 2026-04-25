<?php
namespace App\Services;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class TransactionService {

    public function paginate(Request $filters) : LengthAwarePaginator {
        return Transaction::query()
            ->when($filters['type'] ?? null, fn($q, $type) => $q->type($type))
            ->when(
                $filters['start_date'] ?? null || $filters['end_date'] ?? null,
                fn($q) => $q->dateBetween(
                    $filters['start_date'] ?? null,
                    $filters['end_date'] ?? null
                )
            )
            ->when($filters['description'] ?? null, fn($q, $term) => $q->search($term))
            ->when(
                $filters['min_amount'] ?? null || $filters['max_amount'] ?? null,
                fn($q) => $q->amountBetween(
                    $filters['min_amount'] ?? null,
                    $filters['max_amount'] ?? null
                )
            )
            ->with('category')
            ->paginate(10);
    }

    public function create(array $data) : Transaction {
        return Transaction::create($data);
    }

    public function find(string $id) : ?Transaction {
        return Transaction::find($id);
    }

    public function update(Transaction $transaction, array $data) : Transaction {
        $transaction->update($data);
        return $transaction->fresh();
    }

    public function delete(string $id) : bool {
        return Transaction::where('id', $id)->delete() > 0;
    }

}