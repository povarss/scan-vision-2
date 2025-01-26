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
        'mode',
        'type',
        'pattern_additional_items',
        'double_clicks'
    ];

    protected function casts(): array
    {
        return [
            'pattern' => 'array',
            'pattern_additional_items' => 'array',
            'result' => 'array',
            'custom_settings' => 'array',
            'double_clicks' => 'array',
        ];
    }

    const STATUS_DRAFT = 'draft';
    const STATUS_FINISHED = 'finished';

    const TYPE_STANDARD = 1;

    const TYPE_TRAINING = 2;

    const TYPE_WITH_DOTS = 3;

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

    public function getCorrectPercentage()
    {
        return $this->totalCorrectCount() ? ceil($this->selectedCorrectCount() / $this->totalCorrectCount() * 100) : 0;
    }

    public function getAllSelectedCount()
    {
        return count($this->result);
    }
    public function getSelectedIncorrectCount()
    {
        return $this->getAllSelectedCount() - $this->selectedCorrectCount();
    }

    public function selectedCorrectCount()
    {
        $count = 0;
        foreach ($this->result as $pos) {
            if ($this->pattern[$pos[0]][$pos[1]]['isCorrect']) {
                $count++;
            }
        }
        return  $count;
    }

    public function totalCorrectCount()
    {
        $count = 0;
        if (!empty($this->pattern)) {
            foreach ($this->pattern as $row) {
                foreach ($row as $cell) {
                    if ($cell['isCorrect']) {
                        $count++;
                    }
                }
            }
        }
        return  $count;
    }

    public function createDraft($patientId, $type, $examId)
    {
        $draftExam =  new self();
        $draftExam->patient_id = $patientId;
        $draftExam->setDraftStatus();
        $draftExam->type = $type;
        $draftExam->exam_id = $examId;
        $draftExam->save();
        return $draftExam;
    }

    public function getExamTypeResultLabel()
    {
        return !empty($this->type) ? __('labels.exam_type_result.' . $this->type) : '';
    }

    public function getExamTypeRecommendLabel()
    {
        return !empty($this->type) ? __('labels.exam_type_recommend.' . $this->type) : '';
    }
}
