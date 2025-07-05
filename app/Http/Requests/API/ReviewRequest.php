<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'range_id'     => 'nullable|exists:ranges,id|required_without:other',
            'point_id'  => 'nullable|exists:points,id|required_without:other',
            'latitude'          => 'required|numeric',
            'longitude'         => 'required|numeric',
            'images'       => 'nullable|array',
            'images.*'     => 'required|string',
            'videos'       => 'nullable|array',
            'videos.*'     => 'required|string',
            'other'     => 'nullable|string|max:500|required_without_all:range,point_id',
            'standers'       => 'required|array',
            'standers.*.standard_id'     => 'required|exists:standards,id',
            'standers.*.rate'     => 'required|integer|min:1|max:5',
            'standers.*.description'     => 'nullable|max:500',

            'factors'       => 'nullable|array',
            'factors.*.group_factor_id'     => 'required|exists:group_factors,id',
            'factors.*.value'     => 'required|in:yes,no',
            'factors.*.description'     => 'nullable|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'range_id.exists'       => __('validation.exists', ['attribute' => __('Range')]),
            'range_id.required_without'       => __('validation.required_without', ['attribute' => __('Range'),'values' => __('Other')]),

            'point_id.exists'       => __('validation.exists', ['attribute' => __('Point')]),
            'point_id.required_without'       => __('validation.required_without', ['attribute' => __('Point'),'values' => __('Other')]),

            'latitude.required'          => __('validation.required', ['attribute' => __('Latitude')]),
            'latitude.numeric'           => __('validation.numeric', ['attribute' => __('Latitude')]),

            'longitude.required'         => __('validation.required', ['attribute' => __('Longitude')]),
            'longitude.numeric'          => __('validation.numeric', ['attribute' => __('Longitude')]),

            'images.array'          => __('validation.array', ['attribute' => __('Images')]),

            'videos.array'          => __('validation.array', ['attribute' => __('Videos')]),

            'other.string'          => __('validation.string', ['attribute' => __('Other')]),
            'other.max'             => __('validation.max.string', ['attribute' => __('Other'), 'max' => 500]),
            'other.required_without_all'          => __('validation.required_without_all', ['attribute' => __('Other'),'values' => __('Range') . ', ' . __('Point')]),

            'standers.required' => __('validation.required', ['attribute' => __('Standers')]),
            'standers.array' => __('validation.array', ['attribute' => __('Standers')]),

            'standers.*.standard_id.required' => __('validation.required', ['attribute' => __('Stander')]),
            'standers.*.standard_id.exists' => __('validation.exists', ['attribute' => __('Stander')]),

            'standers.*.rate.required' => __('validation.required', ['attribute' => __('Rate')]),
            'standers.*.rate.integer' => __('validation.integer', ['attribute' => __('Rate')]),
            'standers.*.rate.min' => __('validation.min.numeric', ['attribute' => __('Rate'), 'min' => 1]),
            'standers.*.rate.max' => __('validation.max.numeric', ['attribute' => __('Rate'), 'max' => 5]),

            'standers.*.description.max' => __('validation.max.string', ['attribute' => __('Description'), 'max' => 500]),


            'factors.array' => __('validation.array', ['attribute' => __('Groups')]),
            'factors.*.group_factor_id.required' => __('validation.required', ['attribute' => __('Group')]),
            'factors.*.group_factor_id.exists' => __('validation.exists', ['attribute' => __('Group')]),
            'factors.*.value.required' => __('validation.required', ['attribute' => __('Factor Value')]),
            'factors.*.value.in' => __('validation.in', ['attribute' => __('Factor Value')]),
            'factors.*.description.max' => __('validation.max.string', ['attribute' => __('Description'), 'max' => 500]),


        ];
    }
}
