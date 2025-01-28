<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_ACTIVATED = 'activated';
    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'activated_at' => 'datetime',
            'end_date' => 'datetime',
            'start_at' => 'date',
        ];
    }


    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function isActive()
    {
        return empty($this->end_date) || Carbon::parse($this->end_date)->lessThan(Carbon::now());
    }

    public function activate($patient)
    {
        $activePromo = PromoCode::where('patient_id', $patient->id)
            ->whereDate('end_date', '>=', Carbon::now()->format('Y-m-d'))
            ->orderBy('end_date')
            ->first();
        $this->patient_id = $patient->id;
        $this->activated_at = Carbon::now();
        if (empty($activePromo)) {
            $this->start_at = Carbon::now();
            $this->end_date = Carbon::now()->addDays($this->days);
        } else {
            $this->start_at = $activePromo->end_date->format('Y-m-d');
            $this->end_date = $activePromo->end_date->addDays($this->days);
        }
        $this->save();
    }

    public function remove()
    {
        $this->delete();
    }
}
