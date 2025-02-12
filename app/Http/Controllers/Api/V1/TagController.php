<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Http\Requests\Api\V1\StoreTagRequest;
use App\Http\Requests\Api\V1\UpdateTagRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $cacheKey = 'tags_' . request()->page ?? 1;
        $ttl = config('cache.ttl.tags');

        $tags = Cache::remember($cacheKey, $ttl, function () {
            return Tag::withCount('resources')
                ->latest()
                ->paginate();
        });

        return response()->json($tags);
    }

    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = Tag::create($request->validated());

        return response()->json($tag, Response::HTTP_CREATED);
    }

    public function show(Tag $tag): JsonResponse
    {
        return response()->json(
            $tag->load(['resources'])
        );
    }

    public function update(UpdateTagRequest $request, Tag $tag): JsonResponse
    {
        $tag->update($request->validated());

        return response()->json($tag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
