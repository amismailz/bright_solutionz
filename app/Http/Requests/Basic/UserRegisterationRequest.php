<?php

namespace App\Http\Requests\Basic;

use App\Enums\RoleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;

class UserRegisterationRequest extends FormRequest
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
            'username' => ['required', 'string', 'regex:/^[a-z0-9_]+$/', 'unique:users,username'],
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:10',
            'password' => 'required|string|min:8',
//            'gender' => 'nullable|in:male,female',
            'role' => 'required',
            'range_id' => 'nullable|exists:ranges,id',
            'association_id' => 'required|exists:associations,id',
            'point_ids' => 'nullable|array',
            'point_ids.*' => 'exists:points,id',
        ];
    }
    public function withValidator($validator)
    {
        $validator->sometimes('range_id', 'required', function ($input) {
            return $input->role === RoleTypeEnum::Distributor->name;
        });
        $validator->sometimes('point_ids', 'required', function ($input) {
            return $input->role === RoleTypeEnum::Distributor->name;
        });
    }
    public function messages(): array
    {
        return [
            'name.required' =>  __('validation.required', ['attribute' => __('Name')]),
            'name.string' => __('validation.string', ['attribute' => __('Name')]),
            'name.max' => __('validation.max.string', ['attribute' => __('Name'), 'max' => 255]),
            'username.required' => __('validation.required', ['attribute' => __('User Name')]),
            'username.username' => __('validation.username', ['attribute' => __('User Name')]),
            'username.regex' => __('The username may only contain lowercase letters, numbers, and underscores.'),
            'username.unique' => __('validation.unique', ['attribute' => __('User Name')]),
            'email.required' => __('validation.required', ['attribute' => __('Email')]),
            'email.unique' => __('validation.unique', ['attribute' => __('Email')]),
            'email.email' => __('validation.email', ['attribute' => __('Email')]),
            'email.max' => __('validation.max.string', ['attribute' => __('Email'), 'max' => 255]),
            'phone.string' => __('validation.string', ['attribute' => __('Phone')]),
            'phone.max' => __('validation.max.string', ['attribute' => __('Phone'), 'max' => 10]),
            'password.required' => __('validation.required', ['attribute' => __('Password')]),
            'password.min' => __('validation.min.string', ['attribute' => __('Password'), 'min' => 8]),
            'gender.in' => __('validation.in', ['attribute' => __('Gender')]),
            'role.required' => __('validation.required', ['attribute' => __('Role')]),
            'range_id.required' => __('validation.required', ['attribute' => __('Range')]),
            'range_id.exists' => __('validation.exists', ['attribute' => __('Range')]),
            'association_id.required' => __('validation.required', ['attribute' => __('Association')]),
            'association_id.exists' => __('validation.exists', ['attribute' => __('Association')]),
            'point_ids.*.exists' => __('validation.exists', ['attribute' => __('Points')]),

        ];
    }
}
