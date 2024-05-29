<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends APIRequest
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
        if ($this->isMethod('post')) {
            return [
                'title' => 'required|max:255|string|unique:posts,title',
                'category_id' => 'required|exists:categories,uuid',
                'content' => 'required|string',
            ];
        }
        if ($this->isMethod('patch')) {
            return [
                'title' =>'required|max:255|string|unique:posts,title',
                'category_id' => 'required|exists:categories,uuid',
                'content' => 'required|string',
            ];
        }
        return [];
    }
}
