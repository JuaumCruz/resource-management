<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\getJson;

beforeEach(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/test-validation-exception', function () {
            request()->validate([
                'required_field' => 'required'
            ]);
        });

        Route::get('/test-http-exception', function () {
            throw new HttpException(403, 'Forbidden action');
        });

        Route::get('/test-generic-exception', function () {
            throw new Exception('Generic error');
        });
    });
});

test('validation exceptions are handled correctly', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson('/test-validation-exception');

    $response->assertUnprocessable()
        ->assertJsonStructure([
            'message',
            'errors' => ['required_field']
        ]);
});

test('HTTP exceptions are handled correctly', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson('/test-http-exception');

    $response->assertStatus(403)
        ->assertJson([
            'message' => 'Forbidden action'
        ]);
});

test('generic exceptions return 500 error for API routes', function () {
    $user = User::factory()->create();
    actingAs($user);

    $response = getJson('/test-generic-exception');

    $response->assertStatus(500)
        ->assertJson([
            'message' => 'Generic error'
        ]);
});
