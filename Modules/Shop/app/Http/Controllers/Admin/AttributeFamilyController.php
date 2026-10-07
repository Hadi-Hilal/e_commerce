<?php

namespace Modules\Shop\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Shop\Application\AttributeFamily\AttributeFamilyApplicationService;
use Modules\Shop\Http\Requests\StoreAttributeFamilyRequest;
use Modules\Shop\Http\Requests\UpdateAttributeFamilyRequest;
use Modules\Shop\Models\AttributeFamily;
use Modules\Core\Http\Requests\DeleteMultiRequest;

class AttributeFamilyController extends Controller
{
    public function __construct(
        private readonly AttributeFamilyApplicationService $familyService
    ) {
        $this->setActive('shop');
        $this->setActive('attribute_families');
    }

    public function index()
    {
        $filters = [
            'search' => request()->query('search'),
        ];

        $model = $this->familyService->paginate($filters, [
            'id', 'code', 'name', 'created_at',
        ]);

        $attributes = $this->familyService->getAllAttributes(['id', 'code', 'admin_name']);

        return view('shop::admin.attribute_family.index', compact('model', 'attributes'));
    }

    public function create()
    {
        $attributes = $this->familyService->getAllAttributes(['id', 'code', 'admin_name']);

        return view('shop::admin.attribute_family.create', compact('attributes'));
    }

    public function store(StoreAttributeFamilyRequest $request): RedirectResponse
    {
        $this->familyService->store($request->validated());

        return redirect()->route('admin.attribute_families.index');
    }

    public function edit(AttributeFamily $attribute_family)
    {
        $attributes = $this->familyService->getAllAttributes(['id', 'code', 'admin_name']);
        $selectedAttributes = $attribute_family->attributes()->pluck('attributes.id')->toArray();

        return view('shop::admin.attribute_family.edit', compact('attribute_family', 'attributes', 'selectedAttributes'));
    }

    public function update(UpdateAttributeFamilyRequest $request, AttributeFamily $attribute_family): RedirectResponse
    {
        $updateTranslations = $request->boolean('update_translations');

        $this->familyService->update($attribute_family, $request->validated(), $updateTranslations);

        return redirect()->route('admin.attribute_families.index');
    }

    public function deleteMulti(DeleteMultiRequest $request): RedirectResponse
    {
        $this->familyService->deleteMulti($request->input('ids'));

        return back();
    }
}