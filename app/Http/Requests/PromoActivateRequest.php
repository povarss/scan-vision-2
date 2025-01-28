<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use Illuminate\Foundation\Http\FormRequest;

class PromoActivateRequest extends FormRequest
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
