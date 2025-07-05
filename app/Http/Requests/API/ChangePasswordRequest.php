<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
            'email' => 'required|email',
            'otp' => 'required',
            'password' => 'required|confirmed|min:8'
        ];
    }

    public function messages(): array
    {
        return [
             'email.required' => __('validation.required', ['attribute' => __('Email')]),
             'email.email' => __('validation.email', ['attribute' => __('Email')]),
             'otp.required' => __('validation.required', ['attribute' => __('OTP')]),

             'password.required' => __('validation.required', ['attribute' => __('Password')]),
             'password.min' => __('validation.min.string', ['attribute' => __('Password'), 'min' => 8]),
             'password.confirmed' =>   __('validation.required', ['attribute' => __('Password Confirmation')]),

        ];
    }
}
