<?php

namespace Modules\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slide extends Model
{
    use HasTranslations;

    public $translatable = ['title', 'link_text'];

    protected $fillable = [
        'title',
        'slug',
        'image',
        'link',
        'link_text',
        'status',
        'rank',
    ];

    protected $casts = [
        'rank' => 'integer',
    ];

    protected $appends = ['image_link'];

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'Published');
    }

    public function getImageLinkAttribute(): string
    {
        return $this->resolveMediaPath($this->attributes['image'] ?? null);
    }

    private function resolveMediaPath(?string $path): string
    {
        if ($path) {
            return asset('storage/'.$path);
        }

        return asset('images/blank.png');
    }
}
