<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
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

}
