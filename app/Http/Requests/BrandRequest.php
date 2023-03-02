<?php

namespace App\Http\Requests;

class BrandRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:brand,name',
            'about'  => 'nullable|string|max:1000',
            'facebook' =>'nullable|string',
            'instagram' =>'nullable|string',
            'user_id'=> 'nullable|numeric|exists:user,id',
            'category_id'=> 'nullable|numeric|exists:category,id',
            'youtube'=> 'nullable|string',
            'email'=> 'nullable|email',
            'phone_number' => 'nullable|string',
            'website' => 'nullable|string',
            'image'  => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'photo'=> 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
            'is_band' => 'nullable|boolean'
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
            'name.required'         =>  'Please give Brand name',
            'name.unique'               => 'Please give unique name',
            'about.max'             =>  'Please give Brand about maximum of 1000 characters',
            'email.email'           =>  'Please give Brand email valid',
            'image.image'           =>  'Please give Event image valid',
            'image.mimes'           =>  'Please give Event mimes valid',
            'image.max'             =>  'Please give Event max valid',
            'is_band.boolean'       =>  'Please give Brand is band valid',
            'website.string'        =>  'Please give Brand website valid',
            'photo.image'           =>  'Please give Brand photo valid',
            'youtube.string'        =>  'Please give Brand youtube valid',
            'instagram.string'      =>  'Please give Brand instagram valid',
            'facebook.string'       =>  'Please give Brand facebook valid',
            'category_id.numeric'   =>  'Please give Brand category id valid',
            'user_id.numeric'       =>  'Please give Brand user_id valid',
            'user_id.exists'        =>  'Please give Brand user id valid',
            'category_id.exists'    =>  'Please give Brand category valid',
            'phone_number.string'   =>  'Please give Brand phone number valid',
        ];
    }
}
