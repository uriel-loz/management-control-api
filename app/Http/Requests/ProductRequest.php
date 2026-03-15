<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product_id = $this->route('product')?->id;

        return [
            'name' => 'required|string|max:75',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
            'categories' => 'required|array|min:1',
            'categories.*' => 'uuid|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The field name is required.',
            'name.string' => 'The field name must be a string.',
            'name.max' => 'The field name may not be greater than 75 characters.',
            'price.required' => 'The field price is required.',
            'price.numeric' => 'The field price must be a number.',
            'price.min' => 'The field price must be at least 0.',
            'quantity.required' => 'The field quantity is required.',
            'quantity.integer' => 'The field quantity must be an integer.',
            'quantity.min' => 'The field quantity must be at least 0.',
            'description.string' => 'The field description must be a string.',
            'description.max' => 'The field description may not be greater than 255 characters.',
            'categories.required' => 'At least one category is required.',
            'categories.array' => 'The field categories must be an array.',
            'categories.min' => 'At least one category must be selected.',
            'categories.*.uuid' => 'Each category id must be a valid UUID.',
            'categories.*.exists' => 'One or more selected categories do not exist.',
        ];
    }
}
