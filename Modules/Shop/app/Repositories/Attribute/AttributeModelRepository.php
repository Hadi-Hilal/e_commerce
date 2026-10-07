<?php

namespace Modules\Shop\Repositories\Attribute;

use Config;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Log;
use Modules\Core\Traits\ExceptionHandlerTrait;
use Modules\Shop\Models\Attribute;

class AttributeModelRepository implements AttributeRepository
{
    use ExceptionHandlerTrait;

    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        $query = Attribute::select($columns)->latest();

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%");
            });
        }

        return $query->paginate(Config::get('core.page_size', 30));
    }

    public function find(int $id, array $columns = ['*']): ?Attribute
    {
        return Attribute::find($id, $columns);
    }

    public function store(array $data): mixed
    {
        return $this->execute(function () use ($data) {
            $attributeData = $this->prepareAttributeData($data);
            Attribute::create($attributeData);
            session()->flushMessage(true);
        });
    }

    private function prepareAttributeData(
        array $data,
        ?Attribute $attribute = null,
        bool $updateTranslations = true
    ): array {
        $locale = app()->getLocale();
        $transAdminName = $attribute?->getTranslations('admin_name') ?? [];

        $sourceAdminName = is_array($data['admin_name'])
            ? ($data['admin_name'][$locale] ?? reset($data['admin_name']) ?: '')
            : $data['admin_name'];

        $transAdminName[$locale] = $sourceAdminName;

        if (is_array($data['admin_name'])) {
            foreach ($data['admin_name'] as $lang => $name) {
                if ($name) {
                    $transAdminName[$lang] = $name;
                }
            }
        }

        if ($updateTranslations) {
            foreach (otherLangs() as $lang) {
                try {
                    $transAdminName[$lang] = autoGoogleTranslator($lang, $sourceAdminName);
                } catch (Exception $e) {
                    Log::error($e->getMessage());
                }
            }
        }

        $options = $data['options'] ?? null;
        if (is_array($options)) {
            $options = array_values(array_filter($options, fn($v) => $v !== ''));
            $options = $options ? json_encode($options, JSON_UNESCAPED_UNICODE) : null;
        }

        return array_merge($data, [
            'admin_name' => $transAdminName,
            'options' => $options,
        ]);
    }

    public function update(array $data, Attribute $attribute, bool $updateTranslations = false): mixed
    {
        return $this->execute(function () use ($data, $attribute, $updateTranslations) {
            $attributeData = $this->prepareAttributeData($data, $attribute, $updateTranslations);
            $attribute->update($attributeData);
            session()->flushMessage(true);
            return true;
        });
    }

    public function deleteMulti(array $ids): ?bool
    {
        return $this->execute(function () use ($ids) {
            Attribute::destroy($ids);
            session()->flushMessage(true);
            return true;
        });
    }

    public function getAllAttributes(array $columns = ['*']): \Illuminate\Database\Eloquent\Collection
    {
        return Attribute::select($columns)->orderBy('code')->get();
    }
}