<?php

namespace App\Http\Requests;

use App\Models\MetricQuery;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateMetricQueryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', MetricQuery::class);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'prompt' => 'required|string|min:3|max:500',
            'display_type' => [
                'required',
                'string',
                Rule::in(['table', 'chart', 'metric'])
            ],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'prompt.required' => 'El campo prompt es requerido.',
            'prompt.string' => 'El campo prompt debe ser una cadena de texto.',
            'prompt.min' => 'El campo prompt debe tener al menos 3 caracteres.',
            'prompt.max' => 'El campo prompt no puede superar los 500 caracteres.',
            'display_type.required' => 'El campo display_type es requerido.',
            'display_type.string' => 'El campo display_type debe ser una cadena de texto.',
            'display_type.in' => 'El campo display_type debe ser: table, chart o metric.',
        ];
    }
}
