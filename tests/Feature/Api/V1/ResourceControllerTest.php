<?php

use App\Models\User;
use App\Models\Resource;
use App\Models\Category;
use App\Models\Tag;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\getJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

beforeEach(function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $resource = Resource::factory()->create([
        'category_id' => $category->id
    ]);

    test()->user = $user;
    test()->category = $category;
    test()->resource = $resource;
});

test('unauthenticated users cannot access resources', function () {
    $response = getJson('/api/v1/resources');
    expect($response->status())->toBe(401);
});

test('authenticated users can fetch paginated resources', function () {
    Sanctum::actingAs(test()->user);

    $response = getJson('/api/v1/resources');

    $response->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'description',
                    'slug',
                    'status',
                    'category' => [
                        'id',
                        'name'
                    ],
                    'tags'
                ]
            ],
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
});

test('authenticated users can create new resources', function () {
    Sanctum::actingAs(test()->user);

    $tags = Tag::factory(2)->create();

    $data = [
        'name' => 'New Resource',
        'description' => 'Resource Description',
        'category_id' => test()->category->id,
        'status' => 'draft',
        'tags' => $tags->pluck('id')->toArray()
    ];

    $response = postJson('/api/v1/resources', $data);

    $response->assertCreated()
        ->assertJsonFragment([
            'name' => 'New Resource',
            'description' => 'Resource Description',
            'status' => 'draft'
        ]);

    expect(Resource::where('name', 'New Resource')->exists())->toBeTrue();
});

test('authenticated users can view a single resource', function () {
    Sanctum::actingAs(test()->user);

    $response = getJson("/api/v1/resources/" . test()->resource->id);

    $response->assertOk()
        ->assertJsonStructure([
            'id',
            'name',
            'description',
            'slug',
            'status',
            'category' => [
                'id',
                'name'
            ],
            'tags'
        ]);
});

test('authenticated users can update resources', function () {
    Sanctum::actingAs(test()->user);

    $data = [
        'name' => 'Updated Name',
        'description' => 'Updated Description',
        'category_id' => test()->category->id,
        'status' => 'published'
    ];

    $response = putJson("/api/v1/resources/" . test()->resource->id, $data);

    $response->assertOk()
        ->assertJsonFragment([
            'name' => 'Updated Name',
            'description' => 'Updated Description',
            'status' => 'published'
        ]);

    expect(Resource::find(test()->resource->id))
        ->name->toBe('Updated Name')
        ->description->toBe('Updated Description')
        ->status->toBe('published');
});

test('authenticated users can delete resources', function () {
    Sanctum::actingAs(test()->user);

    $response = deleteJson("/api/v1/resources/" . test()->resource->id);

    $response->assertNoContent();
    expect(Resource::find(test()->resource->id))->toBeNull();
});

test('creating a resource requires valid data', function () {
    Sanctum::actingAs(test()->user);

    $response = postJson('/api/v1/resources', []);

    $response->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'category_id', 'status']);
});
