<?php

namespace Modules\Shop\Repositories\Category;

use Config;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Core\Services\SlugService;
use Modules\Core\Traits\ExceptionHandlerTrait;
use Modules\Core\Traits\FileTrait;
use Modules\Shop\Models\Category;

class CategoryModelRepository implements CategoryRepository
{
    use ExceptionHandlerTrait;
    use FileTrait;

    public function __construct(
        private readonly SlugService $slugService
    ) {}

    public function all(array $columns = ['*'], array $filters = []): LengthAwarePaginator
    {
        $query = Category::query()
            ->with(['attributeFamily:id,name,code', 'parent:id,name,slug'])
            ->select($columns)
            ->latest();

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $locale = app()->getLocale();

            $query->where(function ($q) use ($search, $locale) {
                $q->where('slug', 'like', "%{$search}%")
                    ->orWhere("name->{$locale}", 'like', "%{$search}%")
                    ->orWhere('name->en', 'like', "%{$search}%")
                    ->orWhere('seo_data->title', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['attribute_family_id'])) {
            $query->where('attribute_family_id', $filters['attribute_family_id']);
        }

        if (! empty($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        if (! empty($filters['root']) && filter_var($filters['root'], FILTER_VALIDATE_BOOLEAN)) {
            $query->whereNull('parent_id');
        }

        return $query->paginate(Config::get('core.page_size', 30));
    }

    public function find(int $id, array $columns = ['*']): ?Category
    {
        return Category::select($columns)->find($id);
    }

    public function store(array $data): ?Category
    {
        return $this->execute(function () use ($data) {
            if (empty($data['slug'])) {
                $baseSlug = $this->slugService->generateSlug($data['name'] ?? null);
                $data['slug'] = $this->slugService->generateUniqueSlug($baseSlug, Category::class);
            } else {
                $baseSlug = Str::slug((string) $data['slug']) ?: 'category';
                $data['slug'] = $this->slugService->generateUniqueSlug($baseSlug, Category::class);
            }

            if (! empty($data['seo_data']) && is_array($data['seo_data'])) {
                $data['seo_data'] = $this->formatSeoData($data['seo_data']);
            }

            return Category::create($data);
        });
    }

    public function update(array $data, Category $category): ?Category
    {
        return $this->execute(function () use ($data, $category) {
            if (empty($data['slug'])) {
                $baseSlug = $this->slugService->generateSlug($data['name'] ?? $category->getTranslations('name'));
                $data['slug'] = $this->slugService->generateUniqueSlug($baseSlug, Category::class, $category->id);
            } else {
                $baseSlug = Str::slug((string) $data['slug']) ?: $category->slug;
                $data['slug'] = $this->slugService->generateUniqueSlug($baseSlug, Category::class, $category->id);
            }

            if (! empty($data['seo_data']) && is_array($data['seo_data'])) {
                $data['seo_data'] = $this->formatSeoData($data['seo_data']);
            } elseif (array_key_exists('seo_data', $data)) {
                $data['seo_data'] = [];
            }

            $category->update($data);

            return $category->fresh();
        });
    }

    public function delete(int $id): mixed
    {
        return $this->execute(function () use ($id) {
            $image = Category::whereKey($id)->value('image');
            Category::destroy($id);

            if ($image) {
                $this->deleteFile((string) $image);
            }

            return true;
        });
    }

    public function deleteMulti(array $ids): ?bool
    {
        return $this->execute(function () use ($ids) {
            $ids = array_values(array_unique(array_map('intval', $ids)));

            $blocked = Category::query()
                ->whereIn('parent_id', $ids)
                ->pluck('parent_id')
                ->unique()
                ->all();

            if ($blocked !== []) {
                return false;
            }

            $images = Category::whereIn('id', $ids)->pluck('image')->filter()->all();
            Category::destroy($ids);

            foreach ($images as $image) {
                $this->deleteFile((string) $image);
            }

            return true;
        });
    }

    public function getAllByAttributeFamily(int $attributeFamilyId, array $columns = ['*']): Collection
    {
        return Category::where('attribute_family_id', $attributeFamilyId)
            ->select($columns)
            ->get();
    }

    public function getTree(?int $attributeFamilyId = null, array $columns = ['*']): Collection
    {
        $query = Category::select($columns);

        if ($attributeFamilyId) {
            $query->where('attribute_family_id', $attributeFamilyId);
        }

        return $query->whereNull('parent_id')->get();
    }

    public function getWithEagerLoading(int $id, array $relations = []): ?Category
    {
        return Category::with($relations)->find($id);
    }

    public function getDescendantIds(int $categoryId): array
    {
        return Cache::remember("category_descendants_{$categoryId}", now()->addHours(1), function () use ($categoryId) {
            $ids = [];
            $frontier = [$categoryId];

            while ($frontier !== []) {
                $children = Category::query()
                    ->whereIn('parent_id', $frontier)
                    ->pluck('id')
                    ->all();

                $frontier = array_values(array_diff($children, $ids));
                foreach ($frontier as $id) {
                    $ids[] = $id;
                }
            }

            return $ids;
        });
    }

    public function getRootParents(?int $attributeFamilyId = null, array $columns = ['id', 'slug', 'name']): Collection
    {
        $query = Category::query()->whereNull('parent_id')->select($columns);

        if ($attributeFamilyId) {
            $query->where('attribute_family_id', $attributeFamilyId);
        }

        return $query->orderBy('id')->get();
    }

    private function formatSeoData(array $seo_data): array
    {
        $defaults = [
            'title' => '',
            'description' => '',
            'keywords' => '',
        ];

        return array_merge($defaults, $seo_data);
    }
}
