<?php

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

describe('API Authentication', function () {
    test('unauthenticated users cannot access protected routes', function () {
        $routes = [
            'resources' => getJson('/api/v1/resources'),
            'categories' => getJson('/api/v1/categories'),
            'tags' => getJson('/api/v1/tags')
        ];

        foreach ($routes as $response) {
            $response->assertUnauthorized()
                ->assertJson([
                    'message' => 'Unauthenticated'
                ]);
        }
    });

    test('authenticated users can access protected routes', function () {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $routes = [
            'resources' => getJson('/api/v1/resources'),
            'categories' => getJson('/api/v1/categories'),
            'tags' => getJson('/api/v1/tags')
        ];

        foreach ($routes as $response) {
            $response->assertOk();
        }
    });

    test('routes outside api prefix are not affected by sanctum middleware', function () {
        $response = $this->get('/dashboard');
        $response->assertRedirect(); // Redirects to login if not authenticated
    });
});
