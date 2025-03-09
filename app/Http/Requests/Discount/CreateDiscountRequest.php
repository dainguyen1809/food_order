<?php

namespace App\Http\Requests\Discount;

use Illuminate\Foundation\Http\FormRequest;

class CreateDiscountRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'discount_name' => 'required|string|max:255',
            'discount_description' => 'required|string',
            'discount_code' => 'required|string|unique:discounts,discount_code|max:150',
            'discount_type' => 'required|string|in:percentage,fixed_amount',
            'discount_value' => 'required|numeric|min:0',
            'discount_start_date' => 'required|date',
            'discount_end_date' => 'required|date',
            'discount_max_uses' => 'required|integer|min:0',
            'discount_min_orders_value' => 'required|integer|min:0',
            'discount_max_uses_per_user' => 'required|integer|min:0',
            'discount_max_value' => 'required|integer|min:0',
            'discount_shop' => 'required',
            'discount_is_active' => 'boolean',
            'discount_applies_to' => 'required|in:all,specific',
        ];
    }

    public function messages()
    {
        return [
            'discount_name.required' => 'The discount name is required.',
            'discount_name.string' => 'The discount name must be a string.',
            'discount_name.max' => 'The discount name must not exceed 255 characters.',

            'discount_description.required' => 'The discount description is required.',
            'discount_description.string' => 'The discount description must be a string.',

            'discount_code.required' => 'The discount code is required.',
            'discount_code.string' => 'The discount code must be a string.',
            'discount_code.unique' => 'The discount code has already been taken.',
            'discount_code.max' => 'The discount code must not exceed 150 characters.',

            'discount_type.required' => 'The discount type is required.',
            'discount_type.string' => 'The discount type must be a string.',
            'discount_type.in' => 'The discount type must be either "percentage" or "fixed_amount".',

            'discount_value.required' => 'The discount value is required.',
            'discount_value.numeric' => 'The discount value must be a number.',
            'discount_value.min' => 'The discount value must be at least 0.',

            'discount_start_date.required' => 'The discount start date is required.',
            'discount_start_date.date' => 'The discount start date must be a valid date.',

            'discount_end_date.required' => 'The discount end date is required.',
            'discount_end_date.date' => 'The discount end date must be a valid date.',

            'discount_max_uses.required' => 'The maximum uses of the discount is required.',
            'discount_max_uses.integer' => 'The maximum uses must be an integer.',
            'discount_max_uses.min' => 'The maximum uses must be at least 0.',

            'discount_min_orders_value.required' => 'The minimum order value for the discount is required.',
            'discount_min_orders_value.integer' => 'The minimum order value must be an integer.',
            'discount_min_orders_value.min' => 'The minimum order value must be at least 0.',

            'discount_max_uses_per_user.required' => 'The maximum uses per user is required.',
            'discount_max_uses_per_user.integer' => 'The maximum uses per user must be an integer.',
            'discount_max_uses_per_user.min' => 'The maximum uses per user must be at least 0.',

            'discount_max_value.required' => 'The maximum discount value is required.',
            'discount_max_value.integer' => 'The maximum discount value must be an integer.',
            'discount_max_value.min' => 'The maximum discount value must be at least 0.',

            'discount_is_active.boolean' => 'The discount active status must be true or false.',
        ];
    }


}
