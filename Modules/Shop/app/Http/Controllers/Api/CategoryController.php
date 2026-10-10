<?php

namespace Modules\Shop\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Modules\Shop\Application\Category\CategoryApplicationService;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryApplicationService $categoryService
    ) {}

    public function getParents(Request $request)
    {
        $attributeFamilyId = $request->integer('attribute_family_id') ?: null;

        $parents = CategoryResource::collection(
            $this->categoryService->getRootParents($attributeFamilyId)
        );

        $descendantIds = [];
        $currentId = $request->integer('current_id');
        if ($currentId > 0) {
            $descendantIds = $this->categoryService->getDescendantIds($currentId);
        }

        return response()->json([
            'parents' => $parents,
            'descendantIds' => $descendantIds,
        ]);
    }
}
