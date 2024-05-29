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
                'post_title' => 'required|max:255|string|unique:posts,title',
                'category_id' => 'required|exists:categories,uuid',
                'post_content' => 'required|string',
                'post_excerpt' => 'required|string',
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'main_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        }
        if ($this->isMethod('patch')) {
            return [
                'post_title' => 'required|max:255|string|unique:posts,title',
                'category_id' => 'required|exists:categories,uuid',
                'post_content' => 'required|string',
                'post_excerpt' => 'required|string',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'main_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        }

        if ($this->isMethod('put')) {
            return [
                'post_title' => 'required|max:255|string|unique:posts,title',
                'category_id' => 'required|exists:categories,uuid',
                'post_content' => 'required|string',
                'post_excerpt' => 'required|string',
                'thumbnail' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'main_image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
        }
        return [];
    }


    public function messages()
    {
        return [
            'category_id.exists' => 'The category id you provided does not match our records.',
        ];
    }
}
