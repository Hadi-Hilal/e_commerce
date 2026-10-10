<?php

namespace Modules\Shop\Application\Attribute;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Modules\Shop\Data\AttributeData;
use Modules\Shop\Models\Attribute;
use Modules\Shop\Repositories\Attribute\AttributeRepository;

class AttributeApplicationService
{
    public function __construct(
        private readonly AttributeRepository $repository,
        private readonly FlashMessengerInterface $flashMessenger
    ) {}

    public function paginate(array $filters = [], array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->all($columns, $filters);
    }

    public function store(AttributeData $data): void
    {
        $this->repository->store($data->toArray());
        $this->flashMessenger->success();
    }

    public function update(Attribute $attribute, AttributeData $data, bool $updateTranslations = false): void
    {
        $this->repository->update($data->toArray(), $attribute, $updateTranslations);
        $this->flashMessenger->success();
    }

    public function deleteMulti(array $ids): void
    {
        $this->repository->deleteMulti($ids);
        $this->flashMessenger->success();
    }

    public function getAllAttributes(array $columns = ['*'])
    {
        return $this->repository->getAllAttributes($columns);
    }
}