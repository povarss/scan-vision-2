<?php

namespace App\Services;

use App\Dto\TestAccessDto;
use App\Models\Patient;
use App\Models\PromoCode;
use App\Models\Subscription;
use App\Models\User;

class CheckTestAccessService
{
    public function __construct(public User $user) {}


    public function handle()
    {
        $date = date('Y-m-d');
        $subscription = Subscription::hasActive($this->user, $date);

        if (!empty($subscription)) {
            return new TestAccessDto(
                null,
                $subscription->end_date,
                0,
                $subscription->minutes,
                Subscription::class
            );
        }

        return $this->getPromo($date);
    }

    public function getPromo($date)
    {
        if (!$this->user->hasRole(User::ROLE_PATIENT)) {
            return null;
        }

        $patient = Patient::where('user_id', $this->user->id)->first();

        $promoCode = PromoCode::where('patient_id', $patient->id)
            ->where('activated_at', '>=', $date)
            ->where('end_date', '<=', $date)
            ->first();

        if (!empty($promoCode)) {
            return new TestAccessDto(
                $promoCode->activated_at,
                $promoCode->end_date,
                $promoCode->days,
                $promoCode->minutes,
                PromoCode::class
            );
        }

        return null;
    }
}
