<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UploadAttachmentsRequest extends FormRequest
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
       'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,pdf,mp4,mp3|max:307200',
        ];
    }
    /**
     * Custom messages for validation errors (optional).
     */
    public function messages(): array
    {
        return [
             'file.required' => __('validation.required', ['attribute' => __('File')]),
             'file.image' => __('validation.image', ['attribute' => __('File')]),
             'name.required' =>  __('validation.required', ['attribute' => __('Name')]),
        ];
    }
}
