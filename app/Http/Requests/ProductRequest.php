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
            'slug' => [
                'required',
                'string',
                'max:75',
                Rule::unique('products', 'slug')
                    ->ignore($product_id)
                    ->whereNull('deleted_at'),
            ],
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string|max:255',
            'category_ids' => 'required|array|min:1',
            'category_ids.*' => 'uuid|exists:categories,id',
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
            'price.required' => 'The field price is required.',
            'price.numeric' => 'The field price must be a number.',
            'price.min' => 'The field price must be at least 0.',
            'quantity.required' => 'The field quantity is required.',
            'quantity.integer' => 'The field quantity must be an integer.',
            'quantity.min' => 'The field quantity must be at least 0.',
            'description.string' => 'The field description must be a string.',
            'description.max' => 'The field description may not be greater than 255 characters.',
            'category_ids.required' => 'At least one category is required.',
            'category_ids.array' => 'The field category_ids must be an array.',
            'category_ids.min' => 'At least one category must be selected.',
            'category_ids.*.uuid' => 'Each category id must be a valid UUID.',
            'category_ids.*.exists' => 'One or more selected categories do not exist.',
        ];
    }
}
