<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'doctor_id',
        'full_name',
        'phone',
        'born_date',
        'gender',
        'region_id',
        'field',
        'clinic_diagnose'
    ];

    protected function casts(): array
    {
        return [
            'born_date' => 'date',
        ];
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function patient_answers()
    {
        return $this->hasMany(PatientAnswer::class);
    }

    public function tests()
    {
        return $this->hasMany(PatientExam::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function region()
    {
        return $this->belongsTo(Reference::class)->where('key_', Reference::KEY_REGION);
    }

    public function archive()
    {
        $this->is_archived = 1;
        $this->save();
    }

    public function getDetailFullNameAttribute()
    {
        return $this->full_name;
        // return $this->nick_name . ' ' . $this->full_name;
    }
}
