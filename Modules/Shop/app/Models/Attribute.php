<?php

namespace Modules\Shop\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use HasTranslations;

    public $translatable = ['admin_name'];

    protected $fillable = [
        'code',
        'admin_name',
        'type',
        'is_required',
        'is_unique',
        'options',
    ];

    protected $casts = [
        'admin_name' => 'array',
        'options' => 'array',
        'is_required' => 'boolean',
        'is_unique' => 'boolean',
    ];

    public function attributeFamilies(): BelongsToMany
    {
        return $this->belongsToMany(AttributeFamily::class, 'attribute_family_attributes')
            ->withPivot('sort_order')
            ->withTimestamps()
            ->orderBy('sort_order');
    }
}