<?php

namespace App\Http\Resources;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientAccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subscription = $this->user_id ? Subscription::getByUser($this->user_id) : null;

        $data =  [
            'id' => $this->id,
            'full_name' => $this->full_name,
            'email' => $this->user_id ? $this->user?->email : '',
            'password' => null,
            'expire_at' => $subscription?->end_date->format('Y-m-d'),
            'minutes' => $subscription?->minutes,
        ];
        return $data;
    }
}
