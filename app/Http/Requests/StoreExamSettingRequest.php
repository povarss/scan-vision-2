<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExamSettingRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|int',
            'svg_size' => 'required|int',
            'time' => 'required|int',
            'level' => 'required|string',
            'mode' => 'required|string',
        ];
    }
}
