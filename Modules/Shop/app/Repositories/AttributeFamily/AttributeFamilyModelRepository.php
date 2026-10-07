<?php

namespace Modules\Shop\Repositories\AttributeFamily;

use Config;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;
use Modules\Core\Traits\ExceptionHandlerTrait;
use Modules\Shop\Models\AttributeFamily;

class AttributeFamilyModelRepository implements AttributeFamilyRepository
{
    use ExceptionHandlerTrait;

    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        $query = AttributeFamily::select($columns)->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%");
            });
        }

        return $query->paginate(Config::get('core.page_size', 30));
    }

    public function find(int $id, array $columns = ['*']): ?AttributeFamily
    {
        return AttributeFamily::find($id, $columns);
    }

    public function store(array $data): mixed
    {
        return $this->execute(function () use ($data) {
            $familyData = $this->prepareFamilyData($data);
            $family = AttributeFamily::create($familyData);

            // Sync attributes if provided
            if (isset($data['attribute_ids']) && is_array($data['attribute_ids'])) {
                $this->syncAttributes($family, $data['attribute_ids']);
            }

            session()->flushMessage(true);
        });
    }

    private function prepareFamilyData(
        array $data,
        ?AttributeFamily $family = null,
        bool $updateTranslations = true
    ): array {
        $locale = app()->getLocale();
        $transName = $family?->getTranslations('name') ?? [];

        $sourceName = is_array($data['name'])
            ? ($data['name'][$locale] ?? reset($data['name']) ?: '')
            : $data['name'];

        $transName[$locale] = $sourceName;

        if (is_array($data['name'])) {
            foreach ($data['name'] as $lang => $name) {
                if ($name) {
                    $transName[$lang] = $name;
                }
            }
        }

        if ($updateTranslations) {
            foreach (otherLangs() as $lang) {
                try {
                    $transName[$lang] = autoGoogleTranslator($lang, $sourceName);
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }

        return array_merge($data, [
            'name' => $transName,
        ]);
    }

    private function syncAttributes(AttributeFamily $family, array $attributeIds): void
    {
        $syncData = [];
        foreach ($attributeIds as $index => $attributeId) {
            $syncData[$attributeId] = ['sort_order' => $index];
        }
        $family->attributes()->sync($syncData);
    }

    public function update(array $data, AttributeFamily $family, bool $updateTranslations = false): mixed
    {
        return $this->execute(function () use ($data, $family, $updateTranslations) {
            $familyData = $this->prepareFamilyData($data, $family, $updateTranslations);
            $family->update($familyData);

            // Sync attributes if provided
            if (isset($data['attribute_ids']) && is_array($data['attribute_ids'])) {
                $this->syncAttributes($family, $data['attribute_ids']);
            }

            session()->flushMessage(true);
            return true;
        });
    }

    public function deleteMulti(array $ids): ?bool
    {
        return $this->execute(function () use ($ids) {
            AttributeFamily::destroy($ids);
            session()->flushMessage(true);
            return true;
        });
    }

    public function getAllFamilies(array $columns = ['*']): Collection
    {
        return AttributeFamily::select($columns)->orderBy('code')->get();
    }
}