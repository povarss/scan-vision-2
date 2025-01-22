<?php

namespace App\Http\Requests;

use App\Models\Question;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationSecondStageRequest extends FormRequest
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
        ];
    }

    public function messages()
    {
        return [
            'answers.required' => __('validation.Need answer to all questions'),
            'answers.*.required' => __('validation.Answer the question'),
        ];
    }
}
