<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\HasCache;
use App\Models\Tag;
use App\Http\Requests\Api\V1\StoreTagRequest;
use App\Http\Requests\Api\V1\UpdateTagRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class TagController extends Controller
{
    use HasCache;

    public function index(): JsonResponse
    {
        $page = request()->page ?? 1;
        $cacheKey = "tags.list.page.{$page}";
        $ttl = config('cache.ttl.tags');

        $tags = $this->getCache()->remember($cacheKey, $ttl, function () {
            return Tag::withCount('resources')
                ->latest()
                ->paginate();
        });

        return response()->json($tags);
    }

    public function store(StoreTagRequest $request): JsonResponse
    {
        $tag = Tag::create($request->validated());
        $this->getCache()->flush();

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
        $this->getCache()->flush();

        return response()->json($tag);
    }

    public function destroy(Tag $tag): JsonResponse
    {
        $tag->delete();
        $this->getCache()->flush();

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

    protected function getCacheNames(): array
    {
        return ['tags'];
    }
}
