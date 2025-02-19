<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'product_name' => 'required|string|max:255',
            'product_thumb' => 'required|url',
            'product_description' => 'nullable|string',
            'product_price' => 'required|numeric|min:0',
            'product_quantity' => 'required|integer|min:0',
            'product_type' => 'required|string|in:food,drink,dessert',
            'product_shop' => 'required|exists:users,id',
            'product_attributes' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => 'The product name is required.',
            'product_thumb.required' => 'The product image is required.',
            'product_thumb.url' => 'The product image must be a valid URL.',
            'product_description.string' => 'The description must be a string.',
            'product_price.required' => 'The price is required.',
            'product_price.numeric' => 'The price must be a valid number.',
            'product_price.min' => 'The price must be greater than or equal to 0.',
            'product_quantity.required' => 'The quantity is required.',
            'product_quantity.integer' => 'The quantity must be an integer.',
            'product_quantity.min' => 'The quantity must be greater than or equal to 0.',
            'product_type.required' => 'The product type is required.',
            'product_type.in' => 'The product type must be one of the following: food, drink, or dessert.',
            'product_shop.required' => 'The shop is required.',
            'product_shop.exists' => 'The selected shop does not exist.',
            'product_attributes.required' => 'The product attributes are required.',
        ];
    }
}
