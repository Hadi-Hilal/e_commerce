<?php

namespace Modules\Base\Application\Currency;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Base\Repositories\Currency\CurrencyRepository;
use Modules\Base\Services\FixerCurrencyService;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;

class CurrencyApplicationService
{
    public function __construct(
        private readonly CurrencyRepository $repository,
        private readonly FixerCurrencyService $fixerService,
        private readonly FlashMessengerInterface $flashMessenger
    ) {}

    public function paginate(array $filters = [], array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->paginate($columns, $filters);
    }

    public function find(int $id): ?\Modules\Base\Models\Currency
    {
        return $this->repository->find($id);
    }

    public function store(array $data): \Modules\Base\Models\Currency
    {
        $currency = $this->repository->store($data);
        $this->clearCache();
        $this->flashMessenger->success();

        return $currency;
    }

    public function update(\Modules\Base\Models\Currency $currency, array $data): \Modules\Base\Models\Currency
    {
        $currency = $this->repository->update($currency, $data);
        $this->clearCache();
        $this->flashMessenger->success();

        return $currency;
    }

    public function delete(\Modules\Base\Models\Currency $currency): bool
    {
        $result = $this->repository->delete($currency);

        if ($result) {
            $this->clearCache();
            $this->flashMessenger->success();
        } else {
            $this->flashMessenger->error(__('Cannot delete the default currency.'));
        }

        return $result;
    }

    public function deleteMulti(array $ids): void
    {
        $this->repository->deleteMulti($ids);
        $this->clearCache();
        $this->flashMessenger->success();
    }

    public function setDefault(int $id): bool
    {
        $currency = $this->repository->find($id);

        if (! $currency) {
            $this->flashMessenger->error(__('Currency not found.'));

            return false;
        }

        if (! $currency->is_active) {
            $this->flashMessenger->error(__('Cannot set inactive currency as default.'));

            return false;
        }

        $currency->setAsDefault();
        $this->clearCache();
        $this->flashMessenger->success(__('Default currency updated successfully.'));

        return true;
    }

    public function syncRates(): array
    {
        $result = $this->fixerService->syncRates();
        $this->clearCache();

        if (! empty($result['errors'])) {
            $this->flashMessenger->warning(
                __('Exchange rates synced with some errors. Synced: :count currencies', ['count' => $result['count']])
            );
        } else {
            $this->flashMessenger->success(
                __('Exchange rates synced successfully. Updated: :count currencies', ['count' => $result['count']])
            );
        }

        return $result;
    }

    public function getDefault(): ?\Modules\Base\Models\Currency
    {
        return $this->repository->getDefault();
    }

    private function clearCache(): void
    {
        cache()->forget('currencies');
    }
}