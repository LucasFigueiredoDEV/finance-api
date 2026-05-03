<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

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

        $category = Category::factory()->create([
            'type' => 'income',
        ]);

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

    /**
     * Test if a user can access their own transaction.
     */
    public function test_user_can_show_their_own_transaction(): void
    {
        $user = User::factory()->create();

        $transaction = \App\Models\Transaction::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->getJson("/api/transactions/{$transaction->id}");

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.id',
                $transaction->id
            );
    }

    /**
     * Test if a user can update their own transaction and that the changes are reflected in the database.
     */
    public function test_user_can_update_their_own_transaction(): void
    {
        $user = User::factory()->create();

        $category = Category::factory()->create([
            'type' => 'income',
        ]);

        $transaction = \App\Models\Transaction::factory()->create([
            'user_id' => $user->id,
            'category_id' => $category->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->putJson(
                "/api/transactions/{$transaction->id}",
                [
                    'description' => 'Novo salário',
                    'amount' => 7000,
                    'type' => 'income',
                    'date' => '2026-05-03',
                    'category_id' => $category->id,
                ]
            );

        $response->assertOk();

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'description' => 'Novo salário',
        ]);
    }

    /**
     * Test if a user can delete their own transaction and that the transaction is removed from the database.
     */
    public function test_user_can_delete_their_own_transaction(): void
    {
        $user = User::factory()->create();

        $transaction = \App\Models\Transaction::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this
            ->actingAs($user)
            ->deleteJson(
                "/api/transactions/{$transaction->id}"
            );

        $response->assertOk();

        $this->assertDatabaseMissing('transactions', [
            'id' => $transaction->id,
        ]);
    }
}
