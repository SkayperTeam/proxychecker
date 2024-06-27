<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckProxyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'proxies' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Список прокси обязателен к указанию',
            'string' => 'Список прокси должен быть строкой или массивом',
            'array' => 'Список прокси должен быть строкой или массивом',
        ];
    }
}
