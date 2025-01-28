<?php

namespace App\Services;

use App\Dto\TestAccessDto;
use App\Models\Patient;
use App\Models\PromoCode;
use App\Models\Subscription;
use App\Models\User;
use App\Models\UserTimes;
use Carbon\Carbon;

class CheckTestAccessService
{
    public function __construct(public User $user) {}


    public int $usedTimes = 0;

    public function handle()
    {
        if (empty($this->user)) {
            return null;
        }

        if($this->user->hasRole(User::ROLE_ADMIN)){
            $days = 365;
            return new TestAccessDto(
                null,
                Carbon::now()->addDays($days),
                $days,
                60 * 24,
                0,
                'admin'
            );
        }
        $date = date('Y-m-d');
        $subscription = Subscription::hasActive($this->user, $date);

        $userTime = UserTimes::where(['user_id' => $this->user->id, 'date' => Carbon::now()->format('Y-m-d')])->first();
        if (!empty($userTime)) {
            $this->usedTimes = $userTime->used_time;
        }
        if (!empty($subscription)) {
            return new TestAccessDto(
                null,
                $subscription->end_date,
                $subscription->end_date->diffinDays($subscription->created_at),
                $subscription->minutes,
                $this->usedTimes,
                Subscription::class
            );
        }

        return $this->getPromo($date);
    }

    private function getPromo($date)
    {
        if (!$this->user->hasRole(User::ROLE_PATIENT)) {
            return null;
        }

        $patient = Patient::where('user_id', $this->user->id)->first();

        $promoCode = PromoCode::where('patient_id', $patient->id)
            ->whereDate('activated_at', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->orderBy('end_date')
            ->first();

        if (!empty($promoCode)) {
            return new TestAccessDto(
                $promoCode->activated_at,
                $promoCode->end_date,
                $promoCode->days,
                $promoCode->minutes,
                $this->usedTimes,
                PromoCode::class
            );
        }

        return null;
    }
}
