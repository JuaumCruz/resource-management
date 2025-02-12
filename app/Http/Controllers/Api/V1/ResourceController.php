<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use App\Http\Requests\Api\V1\StoreResourceRequest;
use App\Http\Requests\Api\V1\UpdateResourceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class ResourceController extends Controller
{
    public function index(): JsonResponse
    {
        $cacheKey = 'resources_' . request()->page ?? 1;
        $ttl = config('cache.ttl.resources');

        $resources = Cache::remember($cacheKey, $ttl, function () {
            return Resource::with(['category', 'tags'])
                ->latest()
                ->paginate();
        });

        return response()->json($resources);
    }

    public function store(StoreResourceRequest $request): JsonResponse
    {
        $resource = Resource::create($request->validated());

        if ($request->has('tags')) {
            $resource->tags()->sync($request->tags);
        }

        return response()->json($resource, Response::HTTP_CREATED);
    }

    public function show(Resource $resource): JsonResponse
    {
        return response()->json(
            $resource->load(['category', 'tags'])
        );
    }

    public function update(UpdateResourceRequest $request, Resource $resource): JsonResponse
    {
        $resource->update($request->validated());

        if ($request->has('tags')) {
            $resource->tags()->sync($request->tags);
        }

        return response()->json($resource);
    }

    public function destroy(Resource $resource): JsonResponse
    {
        $resource->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
