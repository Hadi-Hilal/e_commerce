<?php

namespace Modules\Shop\Application\AttributeFamily;

use Illuminate\Database\Eloquent\Collection;
use Modules\Shop\Data\AttributeFamilyData;
use Modules\Shop\Models\AttributeFamily;
use Modules\Shop\Repositories\AttributeFamily\AttributeFamilyRepository;

class AttributeFamilyApplicationService
{
    public function __construct(
        private readonly AttributeFamilyRepository $repository
    ) {}

    public function paginate(array $filters = [], array $columns = ['*']): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->all($columns, $filters);
    }

    public function store(AttributeFamilyData $data): void
    {
        $this->repository->store($data->toArray());
    }

    public function update(AttributeFamily $family, AttributeFamilyData $data, bool $updateTranslations = false): void
    {
        $this->repository->update($data->toArray(), $family, $updateTranslations);
    }

    public function deleteMulti(array $ids): void
    {
        $this->repository->deleteMulti($ids);
    }

    public function getAllFamilies(array $columns = ['*']): Collection
    {
        return $this->repository->getAllFamilies($columns);
    }

    public function getAllAttributes(array $columns = ['*']): Collection
    {
        return \Modules\Shop\Models\Attribute::select($columns)->orderBy('code')->get();
    }
}