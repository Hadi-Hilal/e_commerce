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
        $payload['image'] = $this->resolveImagePath(
            $data->image,
            (string) ($payload['slug'] ?? $category->slug),
            $category->image
        );

        $result = $this->repository->update($payload, $category) !== null;

        if ($result) {
            $this->flashMessenger->success();
            $this->clearDescendantCache($category->id);
            if ($data->parent_id) {
                $this->clearDescendantCache($data->parent_id);
            }
        }

        return $result;
    }

    public function delete(int $id): void
    {
        $category = Category::find($id);
        if ($category && $category->parent_id) {
            $this->clearDescendantCache($category->parent_id);
        }

        $this->repository->delete($id);
        $this->flashMessenger->success();
    }

    public function deleteMulti(array $ids): bool
    {
        // Clear cache for parent categories before deletion
        $categories = Category::whereIn('id', $ids)->get(['id', 'parent_id']);
        foreach ($categories as $category) {
            if ($category->parent_id) {
                $this->clearDescendantCache($category->parent_id);
            }
        }

        $result = (bool) $this->repository->deleteMulti($ids);

        if ($result) {
            $this->flashMessenger->success();
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
        return $this->repository->getTree($attributeFamilyId, $columns);
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
        $query = Category::query()->orderBy('id');

        if ($attributeFamilyId) {
            $query->where('attribute_family_id', $attributeFamilyId);
        }

        $all = $query->get(['id', 'name', 'slug', 'parent_id', 'attribute_family_id', 'image']);

        return $all->whereNull('parent_id')->values()->each(function (Category $root) use ($all) {
            $this->attachChildren($root, $all);
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

    private function attachChildren(Category $node, Collection $all): void
    {
        $children = $all->where('parent_id', $node->id)->values();
        $node->setRelation('children', $children);
        $children->each(fn (Category $child) => $this->attachChildren($child, $all));
    }

    private function clearDescendantCache(int $categoryId): void
    {
        Cache::forget("category_descendants_{$categoryId}");
    }
}
