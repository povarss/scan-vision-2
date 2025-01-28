<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\UserTimes;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class UserTimeCalcJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private User $user)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        UserTimes::calc($this->user);
        //
    }
}
