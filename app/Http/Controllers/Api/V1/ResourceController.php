<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCache;
use App\Models\Resource;
use App\Http\Requests\Api\V1\StoreResourceRequest;
use App\Http\Requests\Api\V1\UpdateResourceRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ResourceController extends Controller
{
    use HasCache;

    public function index(): JsonResponse
    {
        $page = request()->page ?? 1;
        $cacheKey = "resources.list.page.{$page}";
        $ttl = config('cache.ttl.resources');

        $resources = $this->getCache()->remember($cacheKey, $ttl, function () {
            return Resource::with(['category', 'tags'])
                ->latest()
                ->paginate();
        });

        return response()->json($resources);
    }

    public function store(StoreResourceRequest $request): JsonResponse
    {
        $resource = Resource::create($request->validated());
        $this->getCache()->flush();

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
        $this->getCache()->flush();

        if ($request->has('tags')) {
            $resource->tags()->sync($request->tags);
        }

        return response()->json($resource);
    }

    public function destroy(Resource $resource): JsonResponse
    {
        $resource->delete();
        $this->getCache()->flush();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function getCacheNames(): array
    {
        return ['resources'];
    }
}
