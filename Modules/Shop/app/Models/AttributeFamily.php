<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class AttributeFamily extends Model
{
    use HasTranslations;

    public $translatable = ['name'];

    protected $fillable = [
        'code',
        'name',
    ];

    protected $casts = [
        'name' => 'array',
    ];

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_family_attributes')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('sort_order');
    }
}