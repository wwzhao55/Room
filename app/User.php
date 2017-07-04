<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'phone', 'openid','headimg','sex','province','city'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       // 'password', 'remember_token',
    ];

    public function house()
    {
        return $this->hasMany('App\Models\House','user_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment','user_id');
    }

    public function collection()
    {
        return $this->hasMany('App\Models\Collection','user_id');
    }
}
