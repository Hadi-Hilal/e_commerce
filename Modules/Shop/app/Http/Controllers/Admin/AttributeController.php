<?php

namespace Modules\Shop\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Core\Http\Requests\DeleteMultiRequest;
use Modules\Shop\Application\Attribute\AttributeApplicationService;
use Modules\Shop\Http\Requests\StoreAttributeRequest;
use Modules\Shop\Http\Requests\UpdateAttributeRequest;
use Modules\Shop\Models\Attribute;

class AttributeController extends Controller
{
    public function __construct(
        private readonly AttributeApplicationService $attributeService
    ) {
        $this->setActive('shop');
        $this->setActive('attributes');
    }

    public function index()
    {
        $filters = [
            'search' => request()->query('search'),
        ];

        $model = $this->attributeService->paginate($filters, [
            'id', 'code', 'admin_name', 'type', 'is_required', 'is_unique', 'created_at',
        ]);

        return view('shop::admin.attribute.index', compact('model'));
    }

    public function create()
    {
        return view('shop::admin.attribute.create');
    }

    public function store(StoreAttributeRequest $request): RedirectResponse
    {
        $this->attributeService->store($request->validated());

        return redirect()->route('admin.attributes.index');
    }

    public function edit(Attribute $attribute)
    {
        return view('shop::admin.attribute.edit', compact('attribute'));
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute): RedirectResponse
    {
        $updateTranslations = $request->boolean('update_translations');

        $this->attributeService->update($attribute, $request->validated(), $updateTranslations);

        return redirect()->route('admin.attributes.index');
    }

    public function destroy(Attribute $attribute): RedirectResponse
    {
        $this->attributeService->deleteMulti([$attribute->id]);

        return back();
    }

    public function deleteMulti(DeleteMultiRequest $request): RedirectResponse
    {
        $this->attributeService->deleteMulti($request->input('ids'));

        return back();
    }
}
