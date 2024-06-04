<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePostRequest extends APIRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
            return [
                'post_title' => 'max:255|string',
                'category_id' => 'exists:categories,uuid',
                'post_content' => 'string',
                'post_excerpt' => 'string',
            ];

    }


    public function messages()
    {
        return [
            'category_id.exists' => 'The category id you provided does not match our records.',
        ];
    }
}
