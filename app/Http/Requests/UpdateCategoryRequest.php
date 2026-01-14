<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class UpdateCategoryRequest extends FormRequest
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
            'name' => 'nullable|string|max:50',
            'slug' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'name.string' => 'Name must be a string',
            'name.max' => 'Name must be less than 50 characters',
            'slug.string' => 'Slug must be a string',
            'slug.max' => 'Slug must be less than 50 characters',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        return response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'data' => $validator->errors(),
        ], 422);
    }
}
