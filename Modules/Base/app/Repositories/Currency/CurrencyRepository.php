<?php

namespace Modules\Base\Repositories\Currency;

use Illuminate\Support\Collection;
use Modules\Base\Models\Currency;

interface CurrencyRepository
{
    public function all(array $columns = ['*'], array $filters = []): Collection;

    public function paginate(array $columns = ['*'], array $filters = []): \Illuminate\Pagination\LengthAwarePaginator;

    public function find(int $id): ?Currency;

    public function store(array $data): Currency;

    public function update(Currency $currency, array $data): Currency;

    public function delete(Currency $currency): bool;

    public function deleteMulti(array $ids): void;

    public function getDefault(): ?Currency;
}