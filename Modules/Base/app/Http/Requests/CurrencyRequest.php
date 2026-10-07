<?php

namespace Modules\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Base\Data\CurrencyData;

class CurrencyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $currencyId = $this->route('currency')?->id;

        return CurrencyData::rules(
            context: new \Spatie\LaravelData\Support\Validation\ValidationContext(
                payload: $currencyId ? ['id' => $currencyId] : []
            )
        );
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Currency Management');
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'code' => strtoupper($this->input('code')),
        ]);
    }
}