<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLicense extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'dob' => 'required|date_format:Y-m-d|before:today',
            'gender' => 'required|in:MALE,FEMALE',
            'height_ft' => 'required|integer|min:0|max:7',
            'height_in' => 'required|integer|min:0|max:11',
            'weight_lb' => 'required|integer|min:0|max:400',
            'eye_color_id' => 'required|exists:eye_colors,id', // foreign key
            'hair_color_id' => 'required|exists:hair_colors,id', // foreign key
            'address' => 'required|string',
            'sim' => 'required|string',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'max' => 'Your :attribute may not be greater than :max.',
            'min' => 'Your :attribute may not be less than :min.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'dob' => 'date of birth',
            'height_ft' => 'height (ft)',
            'height_in' => 'height (in)',
        ];
    }
}
