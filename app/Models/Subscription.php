<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    // use SoftDeletes;
    protected $guarded = [
        'id',
    ];

    protected function casts(): array
    {
        return [
            'end_date' => 'date',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function store(User $user, $endDate, $minutes)
    {
        $item =    self::firstOrNew([
            'user_id' => $user->id,
        ]);

        $item->end_date = $endDate;
        $item->minutes = $minutes;
        $item->save();
    }

    public static function hasActive($user, $date)
    {
        return self::where('end_date', '>=', $date)->where('user_id', $user->id)->first();
    }

    public static function getByUser($userId){
        return self::where('user_id', $userId)->first();
    }
}
