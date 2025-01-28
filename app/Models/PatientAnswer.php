<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientAnswer extends Model
{
    protected $guarded = [
        'id',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
