<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'born_date' => 'required|date',
            'gender' => 'required|integer|in:0,1',
            'region_id' => 'nullable|integer',
            'field' => 'nullable|string',
            'clinic_diagnose' => 'nullable',
            'tags' => 'nullable|array',
        ];
    }
}
