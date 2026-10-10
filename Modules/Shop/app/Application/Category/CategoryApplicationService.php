<?php

namespace Modules\Shop\Application\Category;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Modules\Core\Traits\FileTrait;
use Modules\Shop\Data\CategoryData;
use Modules\Shop\Models\Category;
use Modules\Shop\Repositories\Category\CategoryRepository;

class CategoryApplicationService
{
    use FileTrait;

    public function __construct(
        private readonly CategoryRepository $repository,
        private readonly FlashMessengerInterface $flashMessenger
    ) {}

    public function paginate(array $filters = [], array $columns = ['*']): LengthAwarePaginator
    {
        return $this->repository->all($columns, $filters);
    }

    public function store(CategoryData $data): bool
    {
        $payload = $this->toPayload($data);
        $removeMetaImage = $this->shouldRemoveMetaImage($payload['seo_data'] ?? []);
        $payload['seo_data'] = $this->resolveSeoImage(
            $payload['seo_data'] ?? [],
            (string) ($payload['slug'] ?? uniqid('category_', true)),
            null,
            $removeMetaImage
        );
        $payload['image'] = $this->resolveImagePath(
            $data->image,
            (string) ($payload['slug'] ?? uniqid('category_', true)),
            null
        );

        $result = $this->repository->store($payload) !== null;

        if ($result) {
            $this->flashMessenger->success();
            if ($data->parent_id) {
                $this->clearDescendantCache($data->parent_id);
            }
        }

        return $result;
    }

    public function update(Category $category, CategoryData $data): bool
    {
        $payload = $this->toPayload($data);
        $oldParentId = $category->parent_id;
        $existingMetaImage = data_get($category->seo_data, 'meta_image');
        $removeMetaImage = $this->shouldRemoveMetaImage($payload['seo_data'] ?? []);
        $payload['seo_data'] = $this->resolveSeoImage(
            $payload['seo_data'] ?? [],
            (string) ($payload['slug'] ?? $category->slug),
            $existingMetaImage,
            $removeMetaImage
        );
        $payload['image'] = $this->resolveImagePath(
            $data->image,
            (string) ($payload['slug'] ?? $category->slug),
            $category->image
        );

        $result = $this->repository->update($payload, $category) !== null;

        if ($result) {
            $this->flashMessenger->success();
            if ($removeMetaImage && $existingMetaImage) {
                $this->deleteFile($existingMetaImage);
            }
            $this->clearDescendantCache($category->id);
            $this->clearDescendantCache($oldParentId);
            if ($data->parent_id) {
                $this->clearDescendantCache($data->parent_id);
            }
        }

        return $result;
    }

    public function delete(int $id): void
    {
        $category = Category::find($id);
        $this->repository->delete($id);

        if ($category) {
            $this->clearDescendantCache($category->id);
            $this->clearDescendantCache($category->parent_id);
        }

        $this->flashMessenger->success();
    }

    public function deleteMulti(array $ids): bool
    {
        $categories = Category::whereIn('id', $ids)->get(['id', 'parent_id']);

        $result = (bool) $this->repository->deleteMulti($ids);

        if ($result) {
            $this->flashMessenger->success();
            foreach ($categories as $category) {
                $this->clearDescendantCache($category->id);
                $this->clearDescendantCache($category->parent_id);
            }
        } else {
            $this->flashMessenger->error(__('Cannot delete categories that still have children.'));
        }

        return $result;
    }

    public function getAllByAttributeFamily(int $attributeFamilyId, array $columns = ['*']): Collection
    {
        return $this->repository->getAllByAttributeFamily($attributeFamilyId, $columns);
    }

    public function getTree(?int $attributeFamilyId = null, array $columns = ['*']): Collection
    {
        $selectedColumns = $columns === ['*']
            ? $columns
            : array_values(array_unique(array_merge($columns, ['id', 'parent_id', 'attribute_family_id'])));
        $query = Category::query()->orderBy('id');
        if ($attributeFamilyId) {
            $query->where('attribute_family_id', $attributeFamilyId);
        }
        $all = $query->get($selectedColumns);
        $tree = $all->whereNull('parent_id')->values();

        $childrenByParent = $all->groupBy('parent_id');

        return $tree->each(function (Category $root) use ($childrenByParent) {
            $this->attachChildren($root, $childrenByParent);
        });
    }

    public function getWithEagerLoading(int $id, array $relations = []): ?Category
    {
        return $this->repository->getWithEagerLoading($id, $relations);
    }

    /**
     * @return list<int>
     */
    public function getDescendantIds(int $categoryId): array
    {
        return $this->repository->getDescendantIds($categoryId);
    }

    public function getRootParents(?int $attributeFamilyId = null, array $columns = ['id', 'slug', 'name']): Collection
    {
        return $this->repository->getRootParents($attributeFamilyId, $columns);
    }

    public function getAllWithChildren(?int $attributeFamilyId = null): Collection
    {
        $columns = ['id', 'name', 'slug', 'parent_id', 'attribute_family_id', 'image'];
        $query = Category::query()->orderBy('id');
        if ($attributeFamilyId) {
            $query->where('attribute_family_id', $attributeFamilyId);
        }
        $all = $query->get($columns);
        $childrenByParent = $all->groupBy('parent_id');

        return $all->whereNull('parent_id')->values()->each(function (Category $root) use ($childrenByParent) {
            $this->attachChildren($root, $childrenByParent);
        });
    }

    /**
     * @return array<string, mixed>
     */
    private function toPayload(CategoryData $data): array
    {
        return [
            'name' => $data->name,
            'attribute_family_id' => $data->attribute_family_id,
            'parent_id' => $data->parent_id,
            'slug' => $data->slug,
            'seo_data' => $data->seo_data,
        ];
    }

    private function resolveImagePath(UploadedFile|string|null $image, string $slug, ?string $existingImage): ?string
    {
        if (is_string($image) && trim($image) !== '') {
            return $image;
        }

        if (! $image) {
            return $existingImage;
        }

        return $this->upload($image, 'categories', $slug, $existingImage);
    }

    /**
     * @param  array<string, mixed>  $seoData
     * @return array<string, mixed>
     */
    private function resolveSeoImage(array $seoData, string $slug, ?string $existingImage): array
    {
        $removeMetaImage = $this->shouldRemoveMetaImage($seoData);
        unset($seoData['_remove_meta_image']);

        if ($removeMetaImage) {
            $seoData['meta_image'] = null;

            return $seoData;
        }

        $metaImage = $seoData['meta_image'] ?? null;
        if (! $metaImage instanceof UploadedFile && ! is_string($metaImage)) {
            $metaImage = null;
        }

        $seoData['meta_image'] = $this->resolveImagePath($metaImage, $slug.'_meta', $existingImage);

        return $seoData;
    }

    /**
     * @param  array<string, mixed>  $seoData
     */
    private function shouldRemoveMetaImage(array $seoData): bool
    {
        return filter_var($seoData['_remove_meta_image'] ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    private function attachChildren(Category $node, Collection $childrenByParent): void
    {
        $children = $childrenByParent->get($node->id, collect())->values();
        $node->setRelation('children', $children);
        $children->each(fn (Category $child) => $this->attachChildren($child, $childrenByParent));
    }

    private function clearDescendantCache(?int $categoryId): void
    {
        $visited = [];
        $currentId = $categoryId;

        while ($currentId !== null && (int) $currentId > 0) {
            $currentId = (int) $currentId;

            if (in_array($currentId, $visited, true)) {
                break;
            }

            $visited[] = $currentId;
            Cache::forget("category_descendants_{$currentId}");
            $currentId = Category::query()->whereKey($currentId)->value('parent_id');
        }
    }
}
