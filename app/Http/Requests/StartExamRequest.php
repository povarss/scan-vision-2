<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StartExamRequest extends FormRequest
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
            'type' => 'required|int',
            'exam_id' => 'required|int',
        ];
    }
}
