<?php

namespace App\Http\Resources;

use App\Services\CheckTestAccessService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserAccessResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // dd($this->user);
        $access = $this->resource ? (new CheckTestAccessService($this->resource))->handle() : null;

        $data = [
            'has_access' => !!$access,
            'end_date' => $access ? $access->endDate->format('d.m.Y') :  null,
            'minutes' => $access ? $access->minutes : null,
            'used_minutes' => $access ? $this->secondsToMinutesSeconds($access->usedSeconds) :  null,
            'end_minutes' => $access ? $this->secondsToMinutesSeconds($access->minutes * 60 - $access->usedSeconds) : null,
        ];
        return $data;
    }
}
