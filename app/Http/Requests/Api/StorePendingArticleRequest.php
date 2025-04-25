<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePendingArticleRequest extends FormRequest
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
            'main_title' => 'required|string|max:255',
            'home_page' => 'required|boolean',
            'most_famous' => 'required|boolean',
            'image_file' => 'required|image|max:2048',
            'articlesubcategorie_id' => 'required|integer|exists:articlesubcategories,id',
            'title' => 'required|array',
            'title.*' => 'required|string|max:255',
            'froala_content' => 'required|array',
            'froala_content.*' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            // Main Title
            'main_title.required' => 'The main title field is required.',
            'main_title.string' => 'The main title must be a string.',
            'main_title.max' => 'The main title may not be greater than 255 characters.',

            // Home Page
            'home_page.required' => 'The home page field is required.',
            'home_page.boolean' => 'The home page field must be true or false.',

            // Most Famous
            'most_famous.required' => 'The most famous field is required.',
            'most_famous.boolean' => 'The most famous field must be true or false.',

            // Image File
            'image_file.required' => 'The image file is required.',
            'image_file.image' => 'The file must be an image.',
            'image_file.max' => 'The image may not be greater than 2MB.',

            // Subcategory
            'articlesubcategorie_id.required' => 'The subcategory field is required.',
            'articlesubcategorie_id.integer' => 'The subcategory must be an integer.',
            'articlesubcategorie_id.exists' => 'The selected subcategory is invalid.',

            // Title Array
            'title.required' => 'The title array is required.',
            'title.array' => 'The title must be an array.',

            // Title Items
            'title.*.required' => 'Each title item is required.',
            'title.*.string' => 'Each title item must be a string.',
            'title.*.max' => 'Each title item may not be greater than 255 characters.',

            // Froala Content Array
            'froala_content.required' => 'The content array is required.',
            'froala_content.array' => 'The content must be an array.',

            // Froala Content Items
            'froala_content.*.required' => 'Each content item is required.',
            'froala_content.*.string' => 'Each content item must be a string.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {

        throw new HttpResponseException(
            response()->json([
                'status' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
