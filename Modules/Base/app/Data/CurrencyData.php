<?php

namespace Modules\Base\Data;

use Closure;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\BooleanType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CurrencyData extends Data
{
    public function __construct(
        #[Required, StringType, Rule('min:3', 'max:3'), Rule('unique:currencies,code')]
        public string $code,

        #[Required, StringType, Rule('min:2', 'max:255')]
        public string $name,

        #[Required, StringType, Rule('min:1', 'max:10')]
        public string $symbol,

        #[Nullable]
        public float $exchange_rate = 1.0,

        #[Nullable, BooleanType]
        public ?bool $is_default = false,

        #[Nullable, BooleanType]
        public ?bool $is_active = true,
    ) {}

    /**
     * Override inferred rules: handle unique rule with ignore on update.
     *
     * @return array<string, list<string|Closure>>
     */
    public static function rules(?ValidationContext $context = null): array
    {
        $rules = [];

        // For update, we need to ignore the current record
        if ($context && isset($context->payload['id'])) {
            $rules['code'] = ['required', 'string', 'min:3', 'max:3', 'unique:currencies,code,' . $context->payload['id']];
        }

        return $rules;
    }
}