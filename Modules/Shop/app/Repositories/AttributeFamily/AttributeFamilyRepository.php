<?php

namespace Modules\Shop\Repositories\AttributeFamily;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Shop\Models\AttributeFamily;

interface AttributeFamilyRepository
{
    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator;

    public function find(int $id, array $columns = ['*']): ?AttributeFamily;

    public function store(array $data): mixed;

    public function update(array $data, AttributeFamily $family, bool $updateTranslations = false): mixed;

    public function deleteMulti(array $ids): ?bool;

    public function getAllFamilies(array $columns = ['*']): Collection;
}