<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class EvaluationRequest extends FormRequest
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
            'note'         => 'nullable|string|max:500',
            'age'          => 'required|integer',
            'images'       => 'nullable|array',
            'images.*'     => 'required|string',
            'videos'       => 'nullable|array',
            'videos.*'     => 'required|string',
            'country_id'     => 'exists:countries,id',
            'point_slug'     => 'nullable|exists:points,slug',
            'standers'       => 'required|array',
            'standers.*.standard_id'     => 'required|exists:standards,id',
            'standers.*.rate'     => 'required|integer|min:1|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'note.string'          => __('validation.string', ['attribute' => __('Notes')]),
            'note.max'             => __('validation.max.string', ['attribute' => __('Notes'), 'max' => 500]),
            'age.required' => __('validation.required', ['attribute' => __('Age')]),
            'age.integer' => __('validation.integer', ['attribute' => __('Age')]),
            'images.array'          => __('validation.array', ['attribute' => __('Images')]),
            'videos.array'          => __('validation.array', ['attribute' => __('Videos')]),
            'point_id.exists' => __('validation.exists', ['attribute' => __('Point')]),
            'country_id.exists' => __('validation.exists', ['attribute' => __('Country')]),
            'standers.required' => __('validation.required', ['attribute' => __('Standers')]),
            'standers.array' => __('validation.array', ['attribute' => __('Standers')]),
            'standers.*.standard_id.required' => __('validation.required', ['attribute' => __('Stander')]),
            'standers.*.standard_id.exists' => __('validation.exists', ['attribute' => __('Stander')]),
            'standers.*.rate.required' => __('validation.required', ['attribute' => __('Rate')]),
            'standers.*.rate.integer' => __('validation.integer', ['attribute' => __('Rate')]),
            'standers.*.rate.min' => __('validation.min.numeric', ['attribute' => __('Rate'), 'min' => 1]),
            'standers.*.rate.max' => __('validation.max.numeric', ['attribute' => __('Rate'), 'max' => 5]),

        ];
    }
}
