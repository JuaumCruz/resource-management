<?php

use App\Models\User;
use App\Models\Tag;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;
use function Pest\Laravel\deleteJson;

beforeEach(function () {
    $user = User::factory()->create();
    test()->user = $user;
});

test('unauthenticated users cannot access tags', function () {
    $response = getJson('/api/v1/tags');
    expect($response->status())->toBe(401);
});

test('can list tags', function () {
    Sanctum::actingAs(test()->user);

    $tags = Tag::factory()->count(3)->create();

    $response = getJson('/api/v1/tags');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
});

test('can create a tag', function () {
    Sanctum::actingAs(test()->user);

    $tagData = [
        'name' => 'Test Tag'
    ];

    $response = postJson('/api/v1/tags', $tagData);

    $response->assertCreated()
        ->assertJson([
            'name' => $tagData['name'],
            'slug' => 'test-tag'
        ]);
});

test('creating a tag requires valid data', function () {
    Sanctum::actingAs(test()->user);

    $response = postJson('/api/v1/tags', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name']);
});

test('can show a tag', function () {
    Sanctum::actingAs(test()->user);

    $tag = Tag::factory()->create();

    $response = getJson("/api/v1/tags/{$tag->id}");

    $response->assertOk()
        ->assertJson([
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug
        ]);
});

test('can update a tag', function () {
    Sanctum::actingAs(test()->user);

    $tag = Tag::factory()->create();
    $updateData = [
        'name' => 'Updated Tag'
    ];

    $response = putJson("/api/v1/tags/{$tag->id}", $updateData);

    $response->assertOk()
        ->assertJson([
            'name' => $updateData['name'],
            'slug' => 'updated-tag'
        ]);
});

test('can delete a tag', function () {
    Sanctum::actingAs(test()->user);

    $tag = Tag::factory()->create();

    $response = deleteJson("/api/v1/tags/{$tag->id}");

    $response->assertNoContent();
    $this->assertSoftDeleted($tag);
});
