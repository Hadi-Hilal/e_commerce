<?php

namespace Modules\Base\Repositories\AdminConfig;

use Illuminate\Support\Collection;
use Modules\Base\Models\AdminConfig;

class AdminConfigModelRepository implements AdminConfigRepository
{
    public function allKeyValue(): Collection
    {
        return AdminConfig::query()->pluck('value', 'key');
    }

    public function get(string $key, ?string $default = null, string $group = null): mixed
    {
        return AdminConfig::get($key, $default, $group);
    }

    public function getGroup(string $group): array
    {
        return AdminConfig::getGroup($group);
    }

    public function set(string $key, ?string $value, string $group = 'general'): bool
    {
        return AdminConfig::set($key, $value, $group);
    }

    public function setGroup(string $group, array $configs): void
    {
        AdminConfig::setGroup($group, $configs);
    }

    public function find(string $key, string $group = 'general'): ?AdminConfig
    {
        return AdminConfig::where('key', $key)->where('group', $group)->first();
    }
}