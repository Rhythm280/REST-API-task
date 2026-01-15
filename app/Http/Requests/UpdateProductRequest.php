<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateProductRequest extends FormRequest
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
            'name' => 'string|max:255',
            'description' => 'string',
            'price' => 'numeric',
            'stock' => 'numeric',
            'category_id' => 'exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Name must be at most 255 characters.',
            'price.numeric' => 'Price must be a number.',
            'stock.numeric' => 'Stock must be a number.',
            'category_id.exists' => 'Category ID does not exist.',
        ];
    }

    public function failedValidation(\Illuminate\Contracts\Validation\Validator $validator): void
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
