<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug'
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($tag) {
            $tag->slug = $tag->slug ?? Str::slug($tag->name);
        });

        static::updating(function ($tag) {
            if ($tag->isDirty('name')) { // Only update slug if name has changed
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Resource::class);
    }
}
