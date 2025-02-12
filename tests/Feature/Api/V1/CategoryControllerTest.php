<?php

use App\Models\User;
use App\Models\Category;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

beforeEach(function () {
    $user = User::factory()->create();
    test()->user = $user;
});

test('unauthenticated users cannot access categories', function () {
    $response = getJson('/api/v1/categories');
    expect($response->status())->toBe(401);
});

test('can list categories', function () {
    Sanctum::actingAs(test()->user);

    $categories = Category::factory()->count(3)->create();

    $response = getJson('/api/v1/categories');

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
    Sanctum::actingAs(test()->user);

    $categoryData = [
        'name' => 'Test Category',
        'description' => 'Test Description'
    ];

    $response = postJson('/api/v1/categories', $categoryData);

    $response->assertCreated()
        ->assertJson([
            'name' => $categoryData['name'],
            'description' => $categoryData['description'],
            'slug' => 'test-category'
        ]);
});

test('creating a category requires valid data', function () {
    Sanctum::actingAs(test()->user);

    $response = postJson('/api/v1/categories', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('can show a category', function () {
    Sanctum::actingAs(test()->user);

    $category = Category::factory()->create();

    $response = getJson("/api/v1/categories/{$category->id}");

    $response->assertOk()
        ->assertJson([
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug
        ]);
});

test('can update a category', function () {
    Sanctum::actingAs(test()->user);

    $category = Category::factory()->create();
    $updateData = [
        'name' => 'Updated Category',
        'description' => 'Updated Description'
    ];

    $response = putJson("/api/v1/categories/{$category->id}", $updateData);

    $response->assertOk()
        ->assertJson([
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'slug' => 'updated-category'
        ]);
});

test('can delete a category', function () {
    Sanctum::actingAs(test()->user);

    $category = Category::factory()->create();

    $response = deleteJson("/api/v1/categories/{$category->id}");

    $response->assertNoContent();
    $this->assertSoftDeleted($category);
});
