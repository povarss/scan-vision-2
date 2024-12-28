<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'id',
        'label',
    ];

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }

    public function getMainTest()
    {
        return self::where(['label' => 'Main test'])->first();
    }
}
