<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    
    const ACCESS_LEVEL_MANAGER =    1;
    // Leave room for more
    const ACCESS_LEVEL_ADMIN =      10;
    
    // Can do this now
    const ACCESS_LEVELS = [
        "manager"   => self::ACCESS_LEVEL_MANAGER,
        "admin"     => self::ACCESS_LEVEL_ADMIN
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'access_level'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function companies() {
        return $this->belongsToMany("App\Company", "user_company" /* used the wrong name */)->withTimestamps();
    }
    
    public function isManager() {
        return $this->access_level === self::ACCESS_LEVEL_MANAGER;
    }
    
    public function isAdmin() {
        return $this->access_level === self::ACCESS_LEVEL_ADMIN;
    }
}
