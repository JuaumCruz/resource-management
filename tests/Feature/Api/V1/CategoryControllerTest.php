<?php

declare(strict_types=1);

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list categories', function () {
    $categories = Category::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/categories');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
});

test('can create a category', function () {
    $categoryData = [
        'name' => 'Test Category',
        'description' => 'Test Description'
    ];

    $response = $this->postJson('/api/v1/categories', $categoryData);

    $response->assertCreated()
        ->assertJson([
            'name' => $categoryData['name'],
            'description' => $categoryData['description'],
            'slug' => 'test-category'
        ]);
});

test('can show a category', function () {
    $category = Category::factory()->create();

    $response = $this->getJson("/api/v1/categories/{$category->id}");

    $response->assertOk()
        ->assertJson([
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug
        ]);
});

test('can update a category', function () {
    $category = Category::factory()->create();
    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description'
    ];

    $response = $this->putJson("/api/v1/categories/{$category->id}", $updateData);

    $response->assertOk()
        ->assertJson([
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'slug' => 'updated-category'
        ]);
});

test('can delete a category', function () {
    $category = Category::factory()->create();

    $response = $this->deleteJson("/api/v1/categories/{$category->id}");

    $response->assertNoContent();
    $this->assertSoftDeleted($category);
});
