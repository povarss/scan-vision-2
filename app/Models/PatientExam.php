<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientExam extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'exam_id',
        'final_result',
        'is_finished',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
