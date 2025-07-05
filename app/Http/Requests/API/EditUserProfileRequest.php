<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class EditUserProfileRequest extends FormRequest
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
        $userId = $this->user()?->id;
        return [
            'name' => 'required|string|max:255',
            //'email' => 'required|email',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->ignore($this->user()->id)
                    ->whereNull('deleted_at'),
            ],
            'username' => [
                'required',
                'string',
                Rule::unique('users', 'username')
                    ->ignore($this->user()->id)
                    ->whereNull('deleted_at'),
            ],
            'phone' => [
                'required',
                'regex:/^5[0-9]{8}$/',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' =>  __('validation.required', ['attribute' => __('Name')]),
            'phone.required' =>  __('validation.required', ['attribute' => __('Phone')]),
            'phone.regex' =>__('The Phone must start with 5 and be followed by exactly 8 digits'),
            'username.required' => __('validation.required', ['attribute' => __('User Name')]),
            'username.unique'=> __('Username already registered'),
            'username.username' => __('validation.username', ['attribute' => __('User Name')]),
            'email.required' => __('validation.required', ['attribute' => __('Email')]),
            'email.unique' => __('Email already in use'),
            'email.email' => __('Please enter a valid email address.'),

        ];
    }
}
