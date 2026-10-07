<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shop\Data\AttributeData;
use Modules\Shop\Models\Attribute;

class UpdateAttributeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('shop.attributes.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $attribute = $this->route('attribute');
        $rules = AttributeData::rules();

        // Remove unique validation for code on update (or ignore current record)
        if ($attribute instanceof Attribute) {
            $rules['code'] = [
                'required',
                'string',
                'min:2',
                'max:100',
                'unique:attributes,code,' . $attribute->id,
            ];
        }

        return $rules;
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