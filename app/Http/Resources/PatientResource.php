<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'born_date' => $this->born_date->format('Y-m-d'),
            'age' => Carbon::parse($this->born_date)->age,
            'gender' => $this->gender,
            'region_id' => $this->region_id,
            'region' => $this->region,
            'field' => $this->field,
            'clinic_diagnose' => $this->clinic_diagnose,
            'tags' => $this->tags,
            'exams' => [
                [
                    'test_id' => 1,
                    'final_result' => 20,
                    'date' => '12.11.2022'
                ],
                [
                    'test_id' => 1,
                    'final_result' => 50,
                    'date' => '11.10.2022'
                ],
                [
                    'test_id' => 1,
                    'final_result' => 20,
                    'date' => '23.04.2022'
                ],
                [
                    'test_id' => 1,
                    'final_result' => 100,
                    'date' => '28.02.2022'
                ],
            ]
        ];
    }
}
