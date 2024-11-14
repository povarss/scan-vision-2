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

    protected function casts(): array
    {
        return [
            'pattern' => 'array',
            'result' => 'array',
        ];
    }

    const STATUS_DRAFT = 'draft';
    const STATUS_FINISHED = 'finished';

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function setDraftStatus()
    {
        $this->status = self::STATUS_DRAFT;
    }

    public function setFinished()
    {
        $this->status = self::STATUS_FINISHED;
    }
}
