<?php

declare(strict_types=1);

use App\Models\Resource;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can list resources', function () {
    $resources = Resource::factory()->count(3)->create();

    $response = $this->getJson('/api/v1/resources');

    $response->assertOk()
        ->assertJsonCount(3, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'description',
                    'category_id',
                    'metadata',
                    'status',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);
});

test('can create a resource', function () {
    $category = Category::factory()->create();
    $tags = Tag::factory()->count(2)->create();

    $resourceData = [
        'name' => 'Test Resource',
        'description' => 'Test Description',
        'category_id' => $category->id,
        'status' => 'draft',
        'tags' => $tags->pluck('id')->toArray(),
    ];

    $response = $this->postJson('/api/v1/resources', $resourceData);

    $response->assertCreated()
        ->assertJson([
            'name' => $resourceData['name'],
            'description' => $resourceData['description'],
            'category_id' => $category->id,
            'status' => 'draft',
            'slug' => 'test-resource'
        ]);

    $this->assertDatabaseHas('resource_tag', [
        'resource_id' => $response->json('id'),
        'tag_id' => $tags[0]->id
    ]);
});

test('can show a resource', function () {
    $resource = Resource::factory()->create();

    $response = $this->getJson("/api/v1/resources/{$resource->id}");

    $response->assertOk()
        ->assertJson([
            'id' => $resource->id,
            'name' => $resource->name,
            'slug' => $resource->slug
        ]);
});

test('can update a resource', function () {
    $resource = Resource::factory()->create();
    $newCategory = Category::factory()->create();

    $updateData = [
        'name' => 'Updated Resource',
        'description' => 'Updated Description',
        'category_id' => $newCategory->id,
        'status' => 'published'
    ];

    $response = $this->putJson("/api/v1/resources/{$resource->id}", $updateData);

    $response->assertOk()
        ->assertJson([
            'name' => $updateData['name'],
            'description' => $updateData['description'],
            'category_id' => $newCategory->id,
            'status' => 'published',
            'slug' => 'updated-resource'
        ]);
});

test('can delete a resource', function () {
    $resource = Resource::factory()->create();

    $response = $this->deleteJson("/api/v1/resources/{$resource->id}");

    $response->assertNoContent();
    $this->assertSoftDeleted($resource);
});
