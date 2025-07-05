<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class MovementRequest extends FormRequest
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
            'quantity' => 'required|integer|min:0|max:2147483647',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'type' => 'required|in:deposit,exchange',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'point_id' => 'required|integer',
            'congestion_level' => 'required|in:high,medium,low',
            'movement_image' => 'required|array',
            'movement_image.*' => 'string',

        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => __('validation.required', ['attribute' => __('Quantity')]),
            'quantity.min' => __('The :attribute must be at least :min.', [
                'attribute' => __('Quantity'),
                'min' => 1,  // your min value here or dynamic
            ]),

            'quantity.max' => __('The :attribute may not be greater than :max.', [
                'attribute' => __('Quantity'),
                'max' => 65535, // your max value here or dynamic
            ]),
            'type.required' => __('validation.required', ['attribute' => __('Type')]),
            'type.in' => __('validation.in', ['attribute' => __('Type')]),

            'lat.required' => __('validation.required', ['attribute' => __('Latitude')]),
            'lat.numeric' => __('validation.numeric', ['attribute' => __('Latitude')]),

            'long.required' => __('validation.required', ['attribute' => __('Longitude')]),
            'long.numeric' => __('validation.numeric', ['attribute' => __('Longitude')]),

            'point_id.required' => __('validation.required', ['attribute' => __('Point')]),
            'point_id.integer' => __('validation.integer', ['attribute' => __('Point')]),

            'association_id.required' => __('validation.required', ['attribute' => __('Association')]),
            'association_id.integer' => __('validation.integer', ['attribute' => __('Association')]),

            'congestion_level.required' => __('validation.required', ['attribute' => __('Congestion Level')]),
            'congestion_level.in' => __('validation.in', ['attribute' => __('Congestion Level')]),

            'movement_image.string' => __('validation.string', ['attribute' => __('Movement Image')]),
            'movement_image.array' => __('Movement images must be an array.'),
            'movement_image.*.string' => __('Each movement image must be a string.'),
        ];
    }
}
