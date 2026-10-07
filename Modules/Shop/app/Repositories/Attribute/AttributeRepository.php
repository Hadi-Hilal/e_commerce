<?php

namespace Modules\Shop\Repositories\Attribute;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Shop\Models\Attribute;

interface AttributeRepository
{
    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator;

    public function find(int $id, array $columns = ['*']): ?Attribute;

    public function store(array $data): mixed;

    public function update(array $data, Attribute $attribute, bool $updateTranslations = false): mixed;

    public function deleteMulti(array $ids): ?bool;

    public function getAllAttributes(array $columns = ['*']): \Illuminate\Database\Eloquent\Collection;
}