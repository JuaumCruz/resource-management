<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;

uses(RefreshDatabase::class);

test('cache is cleared when model is updated', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    // First request
    $firstResponse = $this->actingAs($user)
        ->getJson('/api/v1/categories');

    // Update through API
    $this->actingAs($user)
        ->putJson("/api/v1/categories/{$category->id}", [
            'name' => 'Updated Name',
            'description' => 'Updated Description'
        ]);

    // Second request should show updated data
    $secondResponse = $this->actingAs($user)
        ->getJson('/api/v1/categories');

    expect($secondResponse->json('data.0.name'))->toBe('Updated Name');
});

test('cache is cleared when new category is created', function () {
    $user = User::factory()->create();
    Category::factory()->create(); // Initial category

    // First request
    $firstResponse = $this->actingAs($user)
        ->getJson('/api/v1/categories');

    $initialCount = count($firstResponse->json('data'));

    // Create new category through API
    $this->actingAs($user)
        ->postJson('/api/v1/categories', [
            'name' => 'New Category',
            'description' => 'New Description'
        ]);

    // Second request should include new category
    $secondResponse = $this->actingAs($user)
        ->getJson('/api/v1/categories');

    expect(count($secondResponse->json('data')))->toBe($initialCount + 1);
});

test('cache is cleared when category is deleted', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    // First request
    $firstResponse = $this->actingAs($user)
        ->getJson('/api/v1/categories');

    // Delete through API
    $this->actingAs($user)
        ->deleteJson("/api/v1/categories/{$category->id}");

    // Second request should have empty data
    $secondResponse = $this->actingAs($user)
        ->getJson('/api/v1/categories');

    expect($secondResponse->json('data'))->toBeEmpty();
});
