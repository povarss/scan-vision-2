<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use App\Models\Question;
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
        $questionsCount = Question::count();
        return [
            'name' => 'required|string|max:255',
            'nick_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20|min:12',
            'region_id' => 'required|integer',

            'answers' => [
                'required',
                'array',
                Rule::requiredIf(function () use ($questionsCount) {
                    // Get all questions
                    return $questionsCount > 0; // Check if there are any questions
                }),
                function ($attribute, $value, $fail) use ($questionsCount) {
                    if (count($value) !== $questionsCount) {
                        $fail(__('validation.All questions need bee answered'));
                    }
                },
            ],
            'answers.*' => 'required|integer',

            'email' => ['required', 'email', Rule::unique('users')],
            'password' => 'required|confirmed|min:6',
            'promoCode' => [
                'required',
                'min:1',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $promocode = PromoCode::where('code', $value)
                            ->where('status', PromoCode::STATUS_ACTIVE)
                            ->where('patient_id', 0)
                            ->first();

                        if (!$promocode) {
                            $fail(__('validation.The entered promocode is invalid or already used.'));
                        }
                    }
                },

            ],
        ];
    }
}
