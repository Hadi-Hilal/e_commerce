<?php

namespace Modules\Shop\Data;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class AttributeFamilyData extends Data
{
    public function __construct(
        #[Required, StringType, Rule('min:2', 'max:100', 'unique:attribute_families,code')]
        public string $code,

        #[Required, StringType, Rule('min:2', 'max:255')]
        public string|array $name,

        #[Nullable]
        public array $attribute_ids = [],
    ) {}

    /**
     * Override inferred rules.
     *
     * @return array<string, list<string>>
     */
    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'name' => ['required', 'string|array'],
            'attribute_ids' => ['nullable', 'array'],
            'attribute_ids.*' => ['integer', 'exists:attributes,id'],
        ];
    }

    public static function messages(): array
    {
        return [
            'code.unique' => __('shop::validation.family_code_unique'),
            'code.min' => __('shop::validation.code_min'),
            'name.required' => __('shop::validation.family_name_required'),
            'attribute_ids.*.exists' => __('shop::validation.attribute_not_found'),
        ];
    }
}