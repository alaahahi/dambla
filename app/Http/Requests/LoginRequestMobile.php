<?php

namespace App\Http\Requests;


class LoginRequestMobile extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'phone'    => 'required|numeric|digits:13',
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
        ];
    }
}
