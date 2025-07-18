<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOTPRequest extends FormRequest
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
            'otp' => 'required'
        ];
    }

    public function messages(): array
    {
        return [
             'email.required' => __('validation.required', ['attribute' => __('Email')]),
             'email.email' => __('validation.email', ['attribute' => __('Email')]),
             'otp.required' => __('validation.required', ['attribute' => __('OTP')]),
        ];
    }
}
