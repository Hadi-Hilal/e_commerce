<?php

namespace Modules\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Base\Data\AdminConfigData;

class AdminConfigRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return AdminConfigData::rules();
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('Settings Management');
    }
}