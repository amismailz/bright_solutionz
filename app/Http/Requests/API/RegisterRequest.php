<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'gender' => 'required|in:male,female',
            'password' => 'required|min:8|confirmed',
            'phone' => 'required|regex:/^5[0-9]{8}$/|unique:users',
        ];
    }

    /**
     * Custom messages for validation errors (optional).
     */
    public function messages(): array
    {


        return [
            'name.required' =>  __('validation.required', ['attribute' => __('Name')]),
            'name.string' => __('validation.string', ['attribute' => __('Name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('Name'), 'max' => 255]),

            'gender.required' => __('validation.required', ['attribute' => __('Gender')]),
            'gender.in' => __('validation.in', ['attribute' => __('Gender')]),


             'email.required' => __('validation.required', ['attribute' => __('Email')]),
             'email.unique' => __('validation.unique', ['attribute' => __('Email')]),
             'email.email' => __('validation.email', ['attribute' => __('Email')]),
             'email.max' => __('validation.max.string', ['attribute' => __('Email'), 'max' => 255]),


             'password.required' => __('validation.required', ['attribute' => __('Password')]),
             'password.min' => __('validation.min.string', ['attribute' => __('Password'), 'min' => 8]),
             'password.confirmed' =>   __('validation.required', ['attribute' => __('Password Confirmation')]),


            'phone.required' =>  __('validation.required', ['attribute' => __('Phone')]),
            'phone.regex' => __(':attribute', ['attribute' => __('The Phone must start with 5 and be followed by exactly 8 digits')]),
            'phone.unique' => __('validation.unique', ['attribute' => __('Phone')]),


        ];

    }
}
