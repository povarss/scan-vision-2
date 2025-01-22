<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'nick_name' => 'required|string|max:255',
            'phone' => ['required', 'string', Rule::unique('users')],
            'region_id' => 'required|integer',

            'answers' => 'required|array',
            'answers.*.question_id' => 'required|integer',
            'answers.*.answers_id' => 'required|integer',

            'email' => ['required', 'email', Rule::unique('users')],
            'password' => 'required|confirmed|min:6',
            'promoCode' => [
                'required',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $promocode = PromoCode::where('code', $value)
                            ->where('status', PromoCode::STATUS_ACTIVE)
                            ->whereNull('user_id')
                            ->first();

                        if (!$promocode) {
                            $fail('The entered promocode is invalid or already used.');
                        }
                    }
                },

            ],
        ];
    }
}
