<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shop\Data\CategoryData;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('shop.categories.create');
    }

    public function rules(): array
    {
        return CategoryData::rules();
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
                $seo = json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : $seo;
            }
        }

        $metaImage = $this->file('meta_img') ?: $this->input('meta_img_media_path');
        if ($metaImage !== null && $metaImage !== '') {
            $seo = is_array($seo) ? $seo : [];
            $seo['meta_image'] = $metaImage;
        }

        if ($this->boolean('meta_img_remove') && (is_array($seo) || $seo === null)) {
            $seo = is_array($seo) ? $seo : [];
            $seo['_remove_meta_image'] = true;
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
