<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ContestRequest extends FormRequest
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
            'file'       => 'required|string',
            'description'     => 'nullable|max:500',
            'tags'       => 'required|array',
            'tags.*'     => 'required|exists:tags,id',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required'          => __('validation.required', ['attribute' => __('File')]),
            'description.max' => __('validation.max.string', ['attribute' => __('Description'), 'max' => 500]),
            'tags.required' => __('validation.required', ['attribute' => __('Tags')]),
            'tags.array' => __('validation.array', ['attribute' => __('Tags')]),
            'tags.*.required' => __('validation.required', ['attribute' => __('Tag')]),
            'tags.*.exists' => __('validation.exists', ['attribute' => __('Tag')]),
        ];
    }
}
