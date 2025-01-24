<?php

namespace App\Http\Requests;

use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientAccessRequest extends FormRequest
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
        $patient = Patient::where('id', request()->id)->first();
        $userId = $patient?->user_id;
        return [
            'id' => 'required',
            'full_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($userId)],
            'password' => ($userId ? '' : 'required|'),
            'expire_at' => 'required|date',
            'minutes' => 'required|integer',
        ];
    }
}
