<?php

namespace Modules\Base\Application\AdminConfig;

use Modules\Base\Repositories\AdminConfig\AdminConfigRepository;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;

class AdminConfigApplicationService
{
    public function __construct(
        private readonly AdminConfigRepository $repository,
        private readonly FlashMessengerInterface $flashMessenger
    ) {}

    public function getAll(): array
    {
        return $this->repository->allKeyValue()->toArray();
    }

    public function getGroup(string $group): array
    {
        return $this->repository->getGroup($group);
    }

    public function update(array $data, string $group = 'general'): void
    {
        foreach ($data as $key => $value) {
            if (is_scalar($value) || is_null($value)) {
                $this->repository->set($key, is_null($value) ? null : (string) $value, $group);
            }
        }

        \Modules\Base\Models\AdminConfig::clearCache();
        $this->flashMessenger->success();
    }

    public function find(string $key, string $group = 'general'): ?\Modules\Base\Models\AdminConfig
    {
        return $this->repository->find($key, $group);
    }
}