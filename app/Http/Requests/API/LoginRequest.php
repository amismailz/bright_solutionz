<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'username' => 'required|string|regex:/^\S*$/',
            'password' => 'required|min:8',
            // 'latitude' => 'required|numeric',
            // 'longitude' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => __('validation.required', ['attribute' => __('User Name')]),
            'username.string' => __('validation.required', ['attribute' => __('User Name')]),
            'username.regex' => __('The username must not contain spaces.'),
            //  'username.username' => __('validation.username', ['attribute' => __('User Name')]),
            'password.required' => __('validation.required', ['attribute' => __('Password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('Password'), 'min' => 8]),
        ];
    }
}
