<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LicensePlateRequest extends FormRequest
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
            'style_id' => 'required|exists:license_plate_styles,id', // foreign key
            'make' => 'required|string',
            'model' => 'required|string',
            'class' => 'required|string',
            'color' => 'required|string',
            'year' => 'required|date_format:Y|before:next year',
        ];
    }
}
