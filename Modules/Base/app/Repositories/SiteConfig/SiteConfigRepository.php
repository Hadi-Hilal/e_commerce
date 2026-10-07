<?php

namespace Modules\Base\Repositories\SiteConfig;

use Illuminate\Support\Collection;

interface SiteConfigRepository
{
    public function allKeyValue(): Collection;

    public function get(string $key, ?string $default = null): mixed;

    public function set(string $key, ?string $value): bool;
}
