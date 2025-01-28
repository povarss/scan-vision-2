<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    const ROLE_ADMIN = 'admin';
    const ROLE_DOCTOR = 'doctor';

    const ROLE_PATIENT = 'patient';

    public static function getRoles()
    {
        return [
            self::ROLE_ADMIN,
            self::ROLE_DOCTOR,
            self::ROLE_PATIENT,
        ];
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'expire_at',
    ];

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function patient()
    {
        return $this->hasOne(Patient::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'expire_at' => 'datetime',
        ];
    }

    public function remove()
    {
        $this->delete();
    }

    public static function createPatientUser($nickName, $email, $password, $expireDay)
    {
        $user = new User();
        $user->name = $nickName;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->expire_at = $expireDay;
        $user->save();

        $user->assignRole(self::ROLE_PATIENT);
        return $user;
    }

    public function getFullNameAttribute()
    {
        return $this->hasRole(self::ROLE_PATIENT) ? $this->patient->detail_full_name : $this->name;
    }
}
