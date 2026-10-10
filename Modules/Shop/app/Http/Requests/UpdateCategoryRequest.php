<?php

namespace Modules\Shop\Http\Requests;

use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Shop\Data\CategoryData;
use Modules\Shop\Repositories\Category\CategoryRepository;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('shop.categories.edit');
    }

    public function rules(): array
    {
        $rules = CategoryData::rules();
        $category = $this->route('category');

        $rules['slug'] = [
            'nullable',
            'string',
            'max:255',
            Rule::unique('categories', 'slug')->ignore($category?->id),
        ];

        $rules['parent_id'] = [
            'nullable',
            'integer',
            'exists:categories,id',
            function (string $attribute, mixed $value, Closure $fail) use ($category) {
                if (! $category || $value === null) {
                    return;
                }

                if ((int) $value === (int) $category->id) {
                    $fail(__('A category cannot be its own parent.'));

                    return;
                }

                $descendantIds = app(CategoryRepository::class)->getDescendantIds((int) $category->id);

                if (in_array((int) $value, $descendantIds, true)) {
                    $fail(__('A category cannot be nested under one of its descendants.'));
                }
            },
        ];

        return $rules;
    }

    public function messages(): array
    {
        return CategoryData::messages();
    }

    protected function prepareForValidation(): void
    {
        $parentId = $this->input('parent_id');
        $seo = $this->input('seo_data');

        if (is_string($seo)) {
            $seo = trim($seo);
            if ($seo === '') {
                $seo = null;
            } else {
                $decoded = json_decode($seo, true);
                $seo = json_last_error() === JSON_ERROR_NONE ? $decoded : $seo;
            }
        }

        $this->merge([
            'image' => $this->file('img') ?: $this->input('img_media_path'),
            'parent_id' => $parentId === null || $parentId === '' || (int) $parentId === 0
                ? null
                : (int) $parentId,
            'seo_data' => $seo,
        ]);
    }
}
