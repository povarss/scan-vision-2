<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PatientSettings extends Model
{
    const ST_DIRECTION = 'direction';
    const ST_COLOR = 'color';
    const ST_SPEED = 'speed';
    const ST_DOT_COUNT = 'dot_count';
    protected $fillable = [
        'patient_id',
        'settings',
    ];


    protected function casts(): array
    {
        return [
            'settings' => 'array',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public static function storeParams($patientId, $params)
    {
        if (empty($params)) {
            return;
        }
        $patientSettings =  self::firstOrNew(['patient_id' => $patientId]);
        $patientSettings->settings = !empty($patientSettings->settings) ? array_merge($patientSettings->settings, $params) : $params;
        $patientSettings->save();
    }
}
