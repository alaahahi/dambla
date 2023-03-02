<?php

namespace App\Http\Requests;

class EventRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|max:255|unique:event,name',
            'brand_id' => 'nullable|numeric|exists:brand,id',
            'lat'=>'nullable|string',
            'lng'=>'nullable|string',
            'start'=>'nullable|date',
            'end'=>'nullable|date',
            'entry_fee' =>'nullable|string',
            'titcket_link' =>'nullable|string',
            'about' =>'nullable|string|max:1000',
            'weekly_or_daily' =>'nullable|string',
            'status'=>'nullable|boolean',
            'is_accepted'=>'nullable|boolean',
            'image'=>'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:2048',
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
            'name.required'         =>  'Please give Event name',
            'about.string'          =>  'Please give Event about',
            'about.max'             =>  'Please give Brand about maximum of 1000 characters',
            'brand_id.numeric'      =>  'Please give Event brand id valid',
            'brand_id.exists'       =>  'Please give Event brand id valid',
            'image.image'           =>  'Please give Event image valid',
            'image.mimes'           =>  'Please give Event mimes valid',
            'image.max'             =>  'Please give Event max valid',
            'status.boolean'        =>  'Please give Event is status valid',
            'lat.string'            =>  'Please give Event lat valid ',
            'lng.string'            =>  'Please give Event lng valid',
            'start.date'            =>  'Please give Event start valid',
            'end.date'              =>  'Please give Event end valid',
            'titcket_link.string'   =>  'Please give Event titcket link valid',
            'entry_fee.string'      =>  'Please give Event entry fee valid',
            'weekly_or_daily.string'=>  'Please give Event weekly or daily valid',
            'is_accepted.boolean'   =>  'Please give Event is accepted valid',
        ];
    }
}
