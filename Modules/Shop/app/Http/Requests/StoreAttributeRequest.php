<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shop\Data\AttributeData;

class StoreAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('shop.attributes.create');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return AttributeData::rules();
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return AttributeData::messages();
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'options' => $this->input('options') ?? [],
            'is_required' => $this->boolean('is_required'),
            'is_unique' => $this->boolean('is_unique'),
        ]);
    }
}