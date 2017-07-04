<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'houses';

    protected $fillable = [
        'user_id', 'name', 'door','province','city','district','address','rent','area','type','orientation','latitude','longitude','good_text','bad_text','score','good_img','bad_img'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function getArea()
    {
        return $this->belongsTo('App\Models\HouseArea','area');
    }

    public function getType()
    {
        return $this->belongsTo('App\Models\HouseType','type');
    }

    public function like()
    {
        return $this->hasMany('App\Models\LikeHouse','house_id');
    }

    public function comment()
    {
        return $this->hasMany('App\Models\Comment','house_id');
    }
}
