<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Category;

/**
 * @extends Factory<Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'description' => fake()->sentence(),

            'amount' => fake()->randomFloat(2, 10, 5000),

            'type' => fake()->randomElement([
                'income',
                'expense'
            ]),

            'date' => fake()->date(),

            'user_id' => User::factory(),

            'category_id' => Category::factory(),
        ];
    }
}
