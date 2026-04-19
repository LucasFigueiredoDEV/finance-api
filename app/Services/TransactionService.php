<?php
namespace App\Services;

use App\Models\Transaction;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TransactionService {

    public function paginate() : LengthAwarePaginator {
        return Transaction::paginate(10);
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