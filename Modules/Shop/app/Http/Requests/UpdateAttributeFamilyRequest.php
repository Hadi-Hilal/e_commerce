<?php

namespace Modules\Shop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Shop\Data\AttributeFamilyData;
use Modules\Shop\Models\AttributeFamily;

class UpdateAttributeFamilyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('shop.attribute_families.edit');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $family = $this->route('attribute_family');
        $rules = AttributeFamilyData::rules();

        // Remove unique validation for code on update (or ignore current record)
        if ($family instanceof AttributeFamily) {
            $rules['code'] = [
                'required',
                'string',
                'min:2',
                'max:100',
                'unique:attribute_families,code,' . $family->id,
            ];
        }

        return $rules;
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