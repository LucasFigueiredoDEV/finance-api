<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // INCOME
            ['name' => 'Salary', 'type' => Category::TYPE_INCOME],
            ['name' => 'Freelance', 'type' => Category::TYPE_INCOME],
            ['name' => 'Investments', 'type' => Category::TYPE_INCOME],

            // EXPENSE
            ['name' => 'Food', 'type' => Category::TYPE_EXPENSE],
            ['name' => 'Transport', 'type' => Category::TYPE_EXPENSE],
            ['name' => 'Health', 'type' => Category::TYPE_EXPENSE],
            ['name' => 'Entertainment', 'type' => Category::TYPE_EXPENSE],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['type' => $category['type']]
            );
        }
    }
}