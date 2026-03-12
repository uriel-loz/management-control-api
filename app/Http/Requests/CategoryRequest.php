<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $category_id = $this->route('category')?->id;

        return [
            'name' => 'required|string|max:75',
            'slug' => [
                'required',
                'string',
                'max:75',
                Rule::unique('categories', 'slug')
                    ->ignore($category_id)
                    ->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The field name is required.',
            'name.string' => 'The field name must be a string.',
            'name.max' => 'The field name may not be greater than 75 characters.',
            'slug.required' => 'The field slug is required.',
            'slug.string' => 'The field slug must be a string.',
            'slug.max' => 'The field slug may not be greater than 75 characters.',
            'slug.unique' => 'The slug has already been taken.',
            'description.string' => 'The field description must be a string.',
            'description.max' => 'The field description may not be greater than 255 characters.',
        ];
    }
}
