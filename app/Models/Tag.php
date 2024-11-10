<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public $timestamps = false;
    protected $fillable = [
        'label',
    ];

    public function getTags()
    {
        return self::get()->pluck('label');
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }
}
