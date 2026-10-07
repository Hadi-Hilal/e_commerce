<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shop\Data\AttributeFamilyData;

class StoreAttributeFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('shop.attribute_families.create');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return AttributeFamilyData::rules();
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return AttributeFamilyData::messages();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'attribute_ids' => $this->input('attribute_ids') ?? [],
        ]);
    }
}