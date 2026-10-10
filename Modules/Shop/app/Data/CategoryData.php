<?php

namespace Modules\Shop\Data;

use Closure;
use Illuminate\Http\UploadedFile;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class CategoryData extends Data
{
    public function __construct(
        #[Required]
        public string|array $name,

        #[Required]
        public int $attribute_family_id,

        #[Nullable]
        public ?int $parent_id = null,

        #[Nullable]
        public UploadedFile|string|null $image = null,

        #[Nullable, StringType]
        public ?string $slug = null,

        #[Nullable]
        public ?array $seo_data = null,
    ) {}

    /**
     * Override inferred rules: unions with string pull in a global `string` rule, which rejects file uploads.
     *
     * @return array<string, list<string|Closure>>
     */
    public static function rules(?ValidationContext $context = null): array
    {
        $imageOrPath = function (string $attribute, mixed $value, Closure $fail): void {
            if ($value === null || $value === '') {
                return;
            }
            if ($value instanceof UploadedFile) {
                if (! $value->isValid()) {
                    $fail(__('The :attribute is not a valid file.'));
                }

                return;
            }
            if (! is_string($value)) {
                $fail(__('The :attribute must be a string.'));
            }
        };

        return [
            'name' => ['required'],
            'attribute_family_id' => ['required', 'integer', 'exists:attribute_families,id'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'image' => ['nullable', $imageOrPath],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'seo_data' => ['nullable', 'array'],
        ];
    }

    public static function messages(): array
    {
        return [
            'name.required' => __('shop::validation.name_required'),
            'attribute_family_id.required' => __('shop::validation.attribute_family_required'),
            'attribute_family_id.exists' => __('shop::validation.attribute_family_not_found'),
            'parent_id.exists' => __('shop::validation.parent_category_not_found'),
            'slug.unique' => __('shop::validation.slug_unique'),
        ];
    }
}
