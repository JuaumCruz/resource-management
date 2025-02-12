<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Resource;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ResourceFactory extends Factory
{
    protected $model = Resource::class;

    public function definition(): array
    {
        $name = fake()->unique()->sentence();

        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->paragraph(),
            'category_id' => Category::factory(),
            'metadata' => [
                'version' => fake()->semver(),
                'author' => fake()->name(),
            ],
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
        ];
    }
}
