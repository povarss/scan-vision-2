<?php

namespace App\Models;

use App\Jobs\UserTimeCalcJob;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ExamTimes extends Model
{
    // use SoftDeletes;
    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'counted_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function patientExam()
    {
        return $this->belongsTo(PatientExam::class);
    }

    public static function start(PatientExam $patientExam)
    {
        $examTime =  new self();
        $examTime->patient_id = $patientExam->patient_id;
        $examTime->patient_exam_id = $patientExam->id;
        $examTime->counter = $patientExam->counter;
        $examTime->started_at = Carbon::now();
        $examTime->counted_at = Carbon::now();
        $examTime->save();
        return $examTime;
    }

    public static function setTime(PatientExam $patientExam, $isClose = false)
    {
        $examTime = self::where([
            'patient_exam_id' => $patientExam->id,
            'counter' => $patientExam->counter
        ])->first();
        $examTime->counted_at = Carbon::now();
        if ($isClose) {
            $examTime->finished_at = Carbon::now();
        }
        $examTime->used_seconds = $examTime->started_at->diffInSeconds($examTime->counted_at);
        $examTime->save();

        UserTimeCalcJob::dispatch($patientExam->patient->user);
    }
}
