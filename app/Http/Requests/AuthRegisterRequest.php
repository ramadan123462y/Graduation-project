<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'user_name' => 'required|string|unique:users,user_name|min:3|max:255',
            'email' => 'required|string|email|unique:users,email|min:3|max:255',
            'phone_number' => 'required|string|unique:users,phone_number|min:3|max:255',
            'password' => 'required|string|min:6|max:255|confirmed',
        ];
    }


    public function attributes(): array
{
    return [
        'user_name' =>__('auth.user_name'),
        'email' => __('auth.email'),
         'phone_number'=> __('auth.phone_number'),
          'password'=>__('auth.password'),
        
    ];
}
}
