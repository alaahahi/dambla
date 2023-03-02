<?php

namespace App\Http\Requests;

class CategoryRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'       => 'required|max:255|unique:category,name',
            'order' => 'nullable|numeric',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages(): array
    {
        return [
            'name.required'  => 'Please give Category title',
            'order.numeric'   => 'Please give a numeric Category price',
            'name.unique'  => 'Please give unique name',

        ];
    }
}
