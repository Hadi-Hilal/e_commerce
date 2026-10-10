<?php

namespace Modules\Core\Services;

use Illuminate\Support\Str;

class SlugService
{
    /**
     * Generate a unique slug for a given model.
     *
     * @param string $baseSlug The base slug to generate from
     * @param string $modelClass The fully qualified model class name
     * @param int|null $ignoreId Optional ID to ignore when checking uniqueness (for updates)
     * @return string The generated unique slug
     */
    public function generateUniqueSlug(string $baseSlug, string $modelClass, ?int $ignoreId = null): string
    {
        $base = Str::limit(Str::slug($baseSlug), 200, '');
        $candidate = $base;
        $i = 1;

        while (
            $modelClass::query()
                ->where('slug', $candidate)
                ->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))
                ->exists()
        ) {
            $candidate = $base.'-'.$i++;
        }

        return $candidate;
    }

    /**
     * Generate a slug from a name or string.
     *
     * @param string|array|null $name The name or array of names to slugify
     * @return string The generated slug
     */
    public function generateSlug(string|array|null $name): string
    {
        $source = is_array($name)
            ? (string) ($name[app()->getLocale()] ?? $name['en'] ?? reset($name) ?: '')
            : (string) ($name ?? '');

        $slug = Str::slug($source);

        if ($slug === '') {
            $slug = 'item';
        }

        return $slug;
    }
}
