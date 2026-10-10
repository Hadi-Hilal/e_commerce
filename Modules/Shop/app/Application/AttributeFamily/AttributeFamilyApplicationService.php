<?php

namespace Modules\Shop\Application\AttributeFamily;

use Illuminate\Database\Eloquent\Collection;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Modules\Shop\Data\AttributeFamilyData;
use Modules\Shop\Models\AttributeFamily;
use Modules\Shop\Repositories\AttributeFamily\AttributeFamilyRepository;

class AttributeFamilyApplicationService
{
    public function __construct(
        private readonly AttributeFamilyRepository $repository,
        private readonly FlashMessengerInterface $flashMessenger
    ) {}

    public function paginate(array $filters = [], array $columns = ['*']): \Illuminate\Pagination\LengthAwarePaginator
    {
        return $this->repository->all($columns, $filters);
    }

    public function store(AttributeFamilyData $data): void
    {
        $this->repository->store($data->toArray());
        $this->flashMessenger->success();
    }

    public function update(AttributeFamily $family, AttributeFamilyData $data, bool $updateTranslations = false): void
    {
        $this->repository->update($data->toArray(), $family, $updateTranslations);
        $this->flashMessenger->success();
    }

    public function deleteMulti(array $ids): void
    {
        $this->repository->deleteMulti($ids);
        $this->flashMessenger->success();
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