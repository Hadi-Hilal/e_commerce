<?php

namespace Modules\Base\Repositories\SiteConfig;

use Illuminate\Support\Collection;
use Modules\Base\Models\SiteConfig;

class SiteConfigModelRepository implements SiteConfigRepository
{
    public function allKeyValue(): Collection
    {
        return SiteConfig::query()->pluck('value', 'key');
    }

    public function get(string $key, ?string $default = null): mixed
    {
        return SiteConfig::get($key, $default);
    }

    public function set(string $key, ?string $value): bool
    {
        return SiteConfig::set($key, $value);
    }
}
