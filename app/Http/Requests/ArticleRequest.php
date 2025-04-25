<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ArticleRequest extends FormRequest
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
            'main_title' => 'required',
            'title' => 'required|array',
            'title.*' => 'required',
            'froala_content' => 'required|array',
            'froala_content.*' => 'required',
            'articlesubcategorie_id' => 'required|exists:articlesubcategories,id',
           


        ];
    }

    public function messages(): array
    {
        return [
            'main_title.required' => 'The main title is required.',
            'title.required' => 'The title is required.',
            'title.*.required' => 'Each title in the list is required.',
            'froala_content.required' => 'The content is required.',
            'froala_content.*.required' => 'Each content section is required.',
            'articlesubcategorie_id.required' => 'A subcategory must be selected.',
            'articlesubcategorie_id.exists' => 'The selected subcategory does not exist.',
            'user_id.required' => 'A user must be selected.',
            'user_id.exists' => 'The selected user does not exist.'
        ];
    }
}
