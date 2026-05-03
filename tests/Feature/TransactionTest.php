<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class TransactionTest extends TestCase
{
    use RefreshDatabase;
    /**
     * Test if a user can only see their own transactions and not transactions of other users.
     */
    public function test_user_can_only_see_their_own_transactions(): void
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        \App\Models\Transaction::factory()
            ->count(3)
            ->create([
                'user_id' => $user->id,
            ]);

        \App\Models\Transaction::factory()
            ->count(2)
            ->create([
                'user_id' => $otherUser->id,
            ]);

        $response = $this
            ->actingAs($user)
            ->getJson('/api/transactions');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    /**
     * Test if an authenticated user can create a transaction and that the transaction is associated with the correct user.
     */
    public function test_authenticated_user_can_create_transaction(): void
    {
        $user = User::factory()->create();

        $category = \App\Models\Category::factory()->create();

        $response = $this
            ->actingAs($user)
            ->postJson('/api/transactions', [
                'description' => 'Salário',
                'amount' => 5000,
                'type' => 'income',
                'date' => '2026-05-03',
                'category_id' => $category->id,
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('transactions', [
            'description' => 'Salário',
            'user_id' => $user->id,
        ]);
    }

    /**
     * Test if a user cannot access a transaction that belongs to another user.
     */
    public function test_user_cannot_access_transaction_from_another_user(): void
    {
        $user = User::factory()->create();

        $otherUser = User::factory()->create();

        $transaction = \App\Models\Transaction::factory()->create([
            'user_id' => $otherUser->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson("/api/transactions/{$transaction->id}");

        $response->assertNotFound();
    }
}
