<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\Api\V1\StoreCategoryRequest;
use App\Http\Requests\Api\V1\UpdateCategoryRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $cacheKey = 'categories_' . request()->page ?? 1;
        $ttl = config('cache.ttl.categories');

        $categories = Cache::remember($cacheKey, $ttl, function () {
            return Category::withCount('resources')
                ->latest()
                ->paginate();
        });

        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated());

        return response()->json($category, Response::HTTP_CREATED);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json(
            $category->load(['resources'])
        );
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated());

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
