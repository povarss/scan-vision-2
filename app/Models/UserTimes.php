<?php

namespace App\Models;

use App\Services\CheckTestAccessService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UserTimes extends Model
{
    // use SoftDeletes;
    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function calc(User $user, $date = null)
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }
        $timeInfo =    self::firstOrNew([
            'user_id' => $user->id,
            'date' => $date
        ]);

        if ($user->hasRole(User::ROLE_PATIENT)) {
            $patients = Patient::where('user_id', $user->id)->get();
        } else {
            $patients = Patient::where('doctor_id', $user->id)->get();
            //it's doctor
        }

        $usedSeconds = ExamTimes::whereIn('patient_id', $patients->pluck('id')->toArray())
            ->whereDate('started_at', $date)
            ->sum('used_seconds');
        $timeInfo->used_time = $usedSeconds;
        $checkTimes = (new CheckTestAccessService($user))->handle();
        $timeInfo->limited_time = $checkTimes->minutes * 60;
        $timeInfo->save();
        return $timeInfo;
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
        return $examTime;
    }
}
