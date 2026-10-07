<?php

namespace Modules\Base\Data;

use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;

class AdminConfigData extends Data
{
    public function __construct(
        #[Required, StringType, Rule('min:2', 'max:255')]
        public string $key,

        #[Nullable, StringType]
        public ?string $value = null,

        #[Required, StringType, Rule('min:2', 'max:100')]
        public string $group = 'general',
    ) {}
}