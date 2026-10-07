<?php

namespace Modules\Base\Repositories\AdminConfig;

use Illuminate\Support\Collection;
use Modules\Base\Models\AdminConfig;

interface AdminConfigRepository
{
    public function allKeyValue(): Collection;

    public function get(string $key, ?string $default = null, string $group = null): mixed;

    public function getGroup(string $group): array;

    public function set(string $key, ?string $value, string $group = 'general'): bool;

    public function setGroup(string $group, array $configs): void;

    public function find(string $key, string $group = 'general'): ?AdminConfig;
}