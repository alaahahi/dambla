<?php

namespace App\Http\Requests;


class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name'     => 'string|max:50',
            'email'    => 'max:255|email|unique:users',
            'password' => 'confirmed',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages()
    {
        return [
           
            'name.max'          => 'Please give your name between 50 characters',
       
            'email.unique'      => 'User already exists by this email, please try with another email.',
   
        ];
    }
}
