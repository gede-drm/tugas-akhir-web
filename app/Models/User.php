<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'password', 'role', 'api_token', 'fcm_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $timestamps = false;
    public function unit()
    {
        return $this->hasOne(Unit::class);
    }
    public function security()
    {
        return $this->hasOne(SecurityOfficer::class);
    }
    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }
    public function routeNotificationForFcm()
    {
        try {
            return $this->fcm_token;
        } catch (Exception $e) {
            $this->fcm_token = null;
            $this->save();
        }
    }
}
