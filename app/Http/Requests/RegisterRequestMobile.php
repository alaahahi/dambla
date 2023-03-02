<?php

namespace App\Http\Requests;


class RegisterRequestMobile extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone'    => 'required|unique:users,phone|numeric|digits:13',
            'email'    => 'nullable|email',
            'name'     => 'nullable|string',
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
     * Custom validation message
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'phone.required'    => 'Please give your phone',
            'phone.numeric'    => 'Please give your phone numeric',
            'phone.min' => 'Please give your phone  13 number',
            'email.email' => 'Please give your phone email',
            'name.string' => 'Please give your phone string',
            'phone.unique' => 'Please give your phone is used befor',
        ];
    }
}
