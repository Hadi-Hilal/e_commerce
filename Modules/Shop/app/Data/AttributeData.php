<?php

namespace Modules\Shop\Data;

use Closure;
use Modules\Core\Contracts\Flash\FlashMessengerInterface;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class AttributeData extends Data
{
    public function __construct(
        #[Required, StringType, Rule('min:2', 'max:100', 'unique:attributes,code')]
        public string $code,

        #[Required, StringType, Rule('min:2', 'max:255')]
        public string|array $admin_name,

        #[Required, StringType, Rule('in:text,select,boolean,number,date,textarea')]
        public string $type = 'text',

        #[Nullable, BooleanType]
        public ?bool $is_required = false,

        #[Nullable, BooleanType]
        public ?bool $is_unique = false,

        #[Nullable]
        public array $options = [],
    ) {}

    /**
     * Override inferred rules: unions with string pull in a global `string` rule, which rejects array.
     *
     * @return array<string, list<string|Closure>>
     */
    public static function rules(?ValidationContext $context = null): array
    {
        return [
            'admin_name' => ['required', 'string|array'],
            'options' => ['nullable', 'array'],
            'options.*' => ['string'],
        ];
    }

    public static function messages(): array
    {
        return [
            'code.unique' => __('shop::validation.code_unique'),
            'code.min' => __('shop::validation.code_min'),
            'admin_name.required' => __('shop::validation.admin_name_required'),
            'type.required' => __('shop::validation.type_required'),
            'type.in' => __('shop::validation.type_invalid'),
        ];
    }
}