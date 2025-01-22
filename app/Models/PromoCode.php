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
        ];
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isActive()
    {
        return empty($this->end_date) || Carbon::parse($this->end_date)->lessThan(Carbon::now());
    }

    public function activate($patient)
    {
        $this->patient_id = $patient->id;
        $this->activated_at = Carbon::now();
        $this->end_date = Carbon::now()->addDays($this->days);
        $this->save();
    }

    public function remove()
    {
        $this->delete();
    }
}
