<?php

namespace Modules\Base\Repositories\Currency;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Modules\Base\Models\Currency;

class CurrencyModelRepository implements CurrencyRepository
{
    public function all(array $columns = ['*'], array $filters = []): Collection
    {
        $query = Currency::query();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('code', 'like', "%{$filters['search']}%")
                    ->orWhere('name', 'like', "%{$filters['search']}%")
                    ->orWhere('symbol', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('is_default', 'desc')
            ->orderBy('code')
            ->get($columns);
    }

    public function paginate(array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        $query = Currency::query();

        if (! empty($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('code', 'like', "%{$filters['search']}%")
                    ->orWhere('name', 'like', "%{$filters['search']}%")
                    ->orWhere('symbol', 'like', "%{$filters['search']}%");
            });
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('is_default', 'desc')
            ->orderBy('code')
            ->paginate(20, $columns)
            ->withQueryString();
    }

    public function find(int $id): ?Currency
    {
        return Currency::find($id);
    }

    public function store(array $data): Currency
    {
        // If this is set as default, unset other defaults
        if (! empty($data['is_default'])) {
            return DB::transaction(function () use ($data) {
                Currency::where('is_default', true)->update(['is_default' => false]);
                return Currency::create($data);
            });
        }

        return Currency::create($data);
    }

    public function update(Currency $currency, array $data): Currency
    {
        // If this is set as default, unset other defaults
        if (! empty($data['is_default']) && ! $currency->is_default) {
            return DB::transaction(function () use ($currency, $data) {
                Currency::where('is_default', true)->where('id', '!=', $currency->id)->update(['is_default' => false]);
                $currency->update($data);
                return $currency->fresh();
            });
        }

        $currency->update($data);

        return $currency->fresh();
    }

    public function delete(Currency $currency): bool
    {
        // Prevent deleting the default currency
        if ($currency->is_default) {
            return false;
        }

        return $currency->delete();
    }

    public function deleteMulti(array $ids): void
    {
        Currency::whereIn('id', $ids)
            ->where('is_default', false)
            ->delete();
    }

    public function getDefault(): ?Currency
    {
        return Currency::getDefault();
    }
}