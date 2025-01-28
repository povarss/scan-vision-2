<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $subscription = $this->subscription;
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'expire_at' => $subscription ? $subscription->end_date : $this->expire_at,
            'minutes' => $subscription?->minutes ?? 0
        ];
    }
}
