<?php

declare(strict_types=1);

use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list tags', function () {
    $tags = Tag::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/tags');

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
    $tagData = [
        'name' => 'Test Tag'
    ];

    $response = $this->postJson('/api/v1/tags', $tagData);

    $response->assertCreated()
        ->assertJson([
            'name' => $tagData['name'],
            'slug' => 'test-tag'
        ]);
});

test('can show a tag', function () {
    $tag = Tag::factory()->create();

    $response = $this->getJson("/api/v1/tags/{$tag->id}");

    $response->assertOk()
        ->assertJson([
            'id' => $tag->id,
            'name' => $tag->name,
            'slug' => $tag->slug
        ]);
});

test('can update a tag', function () {
    $tag = Tag::factory()->create();
    $updateData = [
        'name' => 'Updated Tag'
    ];

    $response = $this->putJson("/api/v1/tags/{$tag->id}", $updateData);

    $response->assertOk()
        ->assertJson([
            'name' => $updateData['name'],
            'slug' => 'updated-tag'
        ]);
});

test('can delete a tag', function () {
    $tag = Tag::factory()->create();

    $response = $this->deleteJson("/api/v1/tags/{$tag->id}");

    $response->assertNoContent();
    $this->assertSoftDeleted($tag);
});
