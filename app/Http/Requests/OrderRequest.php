<?php

namespace App\Http\Requests;

use App\Enums\OrderStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', Rule::enum(OrderStatus::class)],
            'user_id' => 'required|uuid|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|uuid|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'The field status is required.',
            'status.string' => 'The field status must be a string.',
            'status.enum' => 'The selected status is invalid. Valid values are: pending, processing, completed, cancelled, refunded.',
            'user_id.required' => 'The field user is required.',
            'user_id.uuid' => 'The field user must be a valid UUID.',
            'user_id.exists' => 'The selected user does not exist.',
            'products.required' => 'The field products is required.',
            'products.array' => 'The field products must be an array.',
            'products.min' => 'The order must have at least one product.',
            'products.*.id.required' => 'Each product must have an id.',
            'products.*.id.uuid' => 'Each product id must be a valid UUID.',
            'products.*.id.exists' => 'One or more selected products do not exist.',
            'products.*.quantity.required' => 'Each product must have a quantity.',
            'products.*.quantity.integer' => 'Each product quantity must be an integer.',
            'products.*.quantity.min' => 'Each product quantity must be at least 1.',
        ];
    }
}
