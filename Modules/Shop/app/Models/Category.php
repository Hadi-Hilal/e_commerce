<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'name',
        'attribute_family_id',
        'parent_id',
        'image',
        'slug',
        'seo_data',
    ];

    protected $casts = [
        'seo_data' => 'array',
    ];

    protected $appends = ['image_link'];

    public function attributeFamily(): BelongsTo
    {
        return $this->belongsTo(AttributeFamily::class, 'attribute_family_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function childrenRecursive(): HasMany
    {
        return $this->children()->with('childrenRecursive');
    }

    public function scopeWithParent($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
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
