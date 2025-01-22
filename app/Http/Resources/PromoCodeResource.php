<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PromoCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'code' => $this->code,
            'days' => $this->days,
            'minutes' => $this->minutes,
            'activated_at' => $this->activated_at,
            'patient_id' => $this->patient_id,
        ];
    }
}
