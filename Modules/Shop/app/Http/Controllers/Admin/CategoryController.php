<?php

namespace Modules\Shop\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Http\Requests\DeleteMultiRequest;
use Modules\Shop\Application\AttributeFamily\AttributeFamilyApplicationService;
use Modules\Shop\Application\Category\CategoryApplicationService;
use Modules\Shop\Data\CategoryData;
use Modules\Shop\Http\Requests\StoreCategoryRequest;
use Modules\Shop\Http\Requests\UpdateCategoryRequest;
use Modules\Shop\Models\Category;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryApplicationService $categoryService,
        private readonly AttributeFamilyApplicationService $attributeFamilyService
    ) {
        $this->setActive('shop');
        $this->setActive('categories');
    }

    public function index()
    {
        $filters = [
            'search' => request()->query('search'),
            'attribute_family_id' => request()->query('attribute_family_id'),
            'root' => request()->query('root'),
        ];

        $model = $this->categoryService->paginate($filters, [
            'id', 'slug', 'name', 'image', 'attribute_family_id', 'parent_id', 'created_at',
        ]);
        return view('shop::admin.category.index', compact('model'));
    }

    public function create()
    {
        $attributeFamilies = $this->attributeFamilyService->getAllFamilies();
        $attributeFamilyId = request()->integer('attribute_family_id') ?: null;
        $tree = $this->categoryService->getTree($attributeFamilyId);

        return view('shop::admin.category.create', compact('attributeFamilies', 'tree'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        if (! $this->categoryService->store(CategoryData::from($request->validated()))) {
            return back()->withInput();
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('shop::messages.category_created'));
    }

    public function edit(Category $category)
    {
        $attributeFamilies = $this->attributeFamilyService->getAllFamilies();
        $descendants = $this->categoryService->getDescendantIds($category->id);
        $tree = $this->categoryService->getTree();
        $disabledIds = array_merge([$category->id], $descendants);

        return view('shop::admin.category.edit', compact('category', 'attributeFamilies', 'tree', 'disabledIds'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        if (! $this->categoryService->update($category, CategoryData::from($request->validated()))) {
            return back()->withInput();
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('shop::messages.category_updated'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $childrenCount = $category->children()->count();

        if ($childrenCount > 0) {
            return back()
                ->with('error', __('shop::messages.category_has_children', ['count' => $childrenCount]));
        }

        $this->categoryService->delete($category->id);

        return back()->with('success', __('shop::messages.category_deleted'));
    }

    public function deleteMulti(DeleteMultiRequest $request): RedirectResponse
    {
        if (! $this->categoryService->deleteMulti($request->input('ids', []))) {
            return redirect()->route('admin.categories.index')
                ->with('error', __('Cannot delete categories that still have children.'));
        }

        return redirect()->route('admin.categories.index')
            ->with('success', __('shop::messages.categories_deleted'));
    }
}
