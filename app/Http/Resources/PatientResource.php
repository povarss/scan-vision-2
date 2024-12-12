<?php

namespace App\Http\Resources;

use App\Models\Exam;
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
        $data =  [
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
            'exams' => [],
            'examTypes' => Exam::get()
        ];
        foreach ($this->tests()->where('status', 'finished')->orderBy('start_time', 'desc')->get() as $test) {
            $data['exams'][] = [
                'test_id' => $test->id,
                'exam_id' => $test->exam_id,
                'final_result' => $test->getCorrectPercentage(),
                'incorrect_count' => $test->getSelectedIncorrectCount(),
                'date' => date('Y-m-d H:i', strtotime($test->start_time))
            ];
        }
        return $data;
    }
}
