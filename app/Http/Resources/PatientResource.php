<?php

namespace App\Http\Resources;

use App\Models\Exam;
use App\Models\Reference;
use App\Services\CheckTestAccessService;
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
        // dd($this->user);
        $access = $this->user_id ? (new CheckTestAccessService($this->user))->handle() : null;

        $data =  [
            'id' => $this->id,
            'doctor_id' => $this->doctor_id,
            'full_name' => $this->full_name,
            'phone' => $this->phone,
            'born_date' => $this->born_date?->format('Y-m-d'),
            'age' => !empty($this->born_date) ? Carbon::parse($this->born_date)->age : '',
            'gender' => $this->gender,
            'region_id' => $this->region_id,
            'region' => $this->region,
            'field' => $this->field,
            'clinic_diagnose' => $this->clinic_diagnose, //Reference::getRefLabel($this->clinic_diagnose),
            'clinic_diagnose_title' => Reference::getRefLabel($this->clinic_diagnose),
            'tags' => $this->tags,
            'exams' => [],
            'examTypes' => Exam::get(),
            'promoCodes' => PatientPromoCodeResource::collection($this->promoCodes()->get()),
            'accessDetail' => new UserAccessResource($this->user),
        ];
        foreach ($this->tests()->where('status', 'finished')->orderBy('start_time', 'desc')->get() as $test) {
            $data['exams'][] = [
                'test_id' => $test->id,
                'type_result_label' => $test->getExamTypeResultLabel(),
                'exam_id' => $test->exam_id,
                'final_result' => $test->getCorrectPercentage(),
                'incorrect_count' => $test->getSelectedIncorrectCount(),
                'date' => date('Y-m-d H:i', strtotime($test->start_time))
            ];
        }
        return $data;
    }
}
