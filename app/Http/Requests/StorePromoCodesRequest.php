<?php

namespace App\Http\Requests;

use App\Models\PromoCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePromoCodesRequest extends FormRequest
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
            'days' => 'required|numeric|min:1|max:100',
            'minutes' => 'required|numeric|min:1|max:' . (60 * 24),
            'codes' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $codes = collect(explode("\n", $value))
                        ->map(function ($item) {
                            return is_string($item) ? trim($item) : $item;
                        })
                        ->filter(function ($item) {
                            return !empty($item);
                        });
                    // dd($codes);
                    if ($codes->isEmpty()) {
                        $fail(__('labels.Please enter promoCodes'));
                    }
                    if ($codes->isNotEmpty()) {
                        $alreadyHasCodes = PromoCode::query()
                            ->whereIn('code', $codes->toArray())
                            ->get();
                        if ($alreadyHasCodes->isNotEmpty()) {
                            $fail(__('labels.This promoCodes already exists') . ":\n" . $alreadyHasCodes->implode('code', ", "));
                        }
                    }
                },
            ]
        ];
    }
}
