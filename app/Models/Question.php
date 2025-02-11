<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    protected $guarded = [
        'id',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
