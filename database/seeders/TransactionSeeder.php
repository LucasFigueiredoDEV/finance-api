<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        $salary = Category::income()->first();
        $food = Category::expense()->where('name', 'Food')->first();
        $transport = Category::expense()->where('name', 'Transport')->first();

        $transactions = [
            [
                'type' => Transaction::TYPE_INCOME,
                'amount' => 5000,
                'description' => 'Monthly salary',
                'date' => Carbon::now()->subDays(10),
                'category_id' => $salary->id,
                'user_id' => $user->id,
            ],
            [
                'type' => Transaction::TYPE_EXPENSE,
                'amount' => 120.50,
                'description' => 'Grocery shopping',
                'date' => Carbon::now()->subDays(5),
                'category_id' => $food->id,
                'user_id' => $user->id,
            ],
            [
                'type' => Transaction::TYPE_EXPENSE,
                'amount' => 50,
                'description' => 'Uber rides',
                'date' => Carbon::now()->subDays(2),
                'category_id' => $transport->id,
                'user_id' => $user->id,
            ],
        ];

        foreach ($transactions as $transaction) {
            Transaction::firstOrCreate(
                [
                    'description' => $transaction['description'],
                    'amount' => $transaction['amount'],
                    'date' => $transaction['date'],
                ],
                $transaction
            );
        }
    }
}