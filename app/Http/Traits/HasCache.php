<?php

declare(strict_types=1);

namespace App\Http\Traits;

use Illuminate\Cache\TaggedCache;
use Illuminate\Support\Facades\Cache;

trait HasCache
{
    abstract protected function getCacheNames(): array;

    protected function getCache(): TaggedCache
    {
        return Cache::tags($this->getCacheNames());
    }
}
