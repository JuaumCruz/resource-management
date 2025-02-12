<?php

declare(strict_types=1);

namespace Tests\Feature\Api;

use App\Models\User;
use Laravel\Sanctum\Sanctum;
use function Pest\Laravel\getJson;

describe('Security Headers', function () {
    test('security headers are added to API routes', function () {
        // Authenticate to pass Sanctum middleware
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = getJson('/api/v1/resources');

        $expectedHeaders = [
            'X-XSS-Protection' => '1; mode=block',
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains'
        ];

        foreach ($expectedHeaders as $header => $value) {
            $response->assertHeader($header, $value);
        }
    });
});
