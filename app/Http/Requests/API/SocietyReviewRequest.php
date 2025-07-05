<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class SocietyReviewRequest extends FormRequest
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
            'society_name'     => 'required|string',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'images'       => 'nullable|array',
            'images.*'     => 'required|string',
            'videos'       => 'nullable|array',
            'videos.*'     => 'required|string',

            'factors'       => 'required|array',
            'factors.*.society_group_factor_id'     => 'required|exists:society_group_factors,id',
            'factors.*.value'     => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'society_name.required'          => __('validation.required', ['attribute' => __('Association')]),

            'latitude.required'          => __('validation.required', ['attribute' => __('Latitude')]),
            'latitude.numeric'           => __('validation.numeric', ['attribute' => __('Latitude')]),

            'longitude.required'         => __('validation.required', ['attribute' => __('Longitude')]),
            'longitude.numeric'          => __('validation.numeric', ['attribute' => __('Longitude')]),

            'images.array'          => __('validation.array', ['attribute' => __('Images')]),

            'videos.array'          => __('validation.array', ['attribute' => __('Videos')]),


            'factors.required' => __('validation.required', ['attribute' => __('Groups')]),
            'factors.array' => __('validation.array', ['attribute' => __('Groups')]),
            'factors.*.society_group_factor_id.required' => __('validation.required', ['attribute' => __('Group')]),
            'factors.*.society_group_factor_id.exists' => __('validation.exists', ['attribute' => __('Group')]),
            'factors.*.value.required' => __('validation.required', ['attribute' => __('Factor Value')]),
        ];
    }
}
