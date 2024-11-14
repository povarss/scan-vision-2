<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientExam extends Model
{
    // use SoftDeletes;
    protected $fillable = [
        'patient_id',
        'exam_id',
        'final_result',
        'is_finished',
        'time',
        'svg_size',
        'level',
        'mode'
    ];

    const STATUS_DRAFT = 'draft';

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function setDraftStatus()
    {
        $this->status = self::STATUS_DRAFT;
    }
}
