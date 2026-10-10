<?php

namespace Modules\Shop\Repositories\Category;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Shop\Models\Category;

interface CategoryRepository
{
    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator;

    public function find(int $id, array $columns = ['*']): ?Category;

    public function store(array $data): ?Category;

    public function update(array $data, Category $category): ?Category;

    public function delete(int $id): mixed;

    public function deleteMulti(array $ids): ?bool;

    public function getAllByAttributeFamily(int $attributeFamilyId, array $columns = ['*']): Collection;

    public function getTree(?int $attributeFamilyId = null, array $columns = ['*']): Collection;

    public function getWithEagerLoading(int $id, array $relations = []): ?Category;

    /**
     * @return list<int>
     */
    public function getDescendantIds(int $categoryId): array;

    public function getRootParents(?int $attributeFamilyId = null, array $columns = ['id', 'slug', 'name']): Collection;
}
