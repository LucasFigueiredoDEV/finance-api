<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use Tests\TestCase;
use App\Models\User;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_can_list_categories(): void
    {
        Category::factory()
            ->count(3)
            ->create();

        $response = $this->actingAs($this->user)->getJson('/api/categories');

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function test_can_create_category(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postJson('/api/categories', [
                'name' => 'Salário',
                'type' => 'income',
            ]);

        $response->assertCreated();

        $this->assertDatabaseHas('categories', [
            'name' => 'Salário',
            'type' => 'income',
        ]);
    }

    public function test_can_show_category(): void
    {
        $category = Category::factory()->create();

        $response = $this
            ->actingAs($this->user)
            ->getJson("/api/categories/{$category->id}");

        $response
            ->assertOk()
            ->assertJsonPath(
                'data.id',
                $category->id
            );
    }

    public function test_can_update_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)
            ->putJson(
                "/api/categories/{$category->id}",
                [
                    'name' => 'Investimentos',
                    'type' => 'income',
                ]
            );

        $response->assertOk();

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Investimentos',
        ]);
    }

    public function test_can_delete_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->deleteJson(
            "/api/categories/{$category->id}"
        );

        $response->assertOk();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);
    }

    public function test_returns_404_when_category_not_found(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getJson('/api/categories/999');

        $response->assertNotFound();
    }
}
