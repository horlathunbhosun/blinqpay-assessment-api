<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends APIRequest
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
                'name' => 'required|max:255|string:unique:categories,name',
            ];
        }
        if ($this->isMethod('patch')) {
            return [
                'name' => 'required|max:255|string:unique:categories,name',
            ];
        }
           return [];
    }
}
