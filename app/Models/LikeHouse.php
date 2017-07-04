<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LikeHouse extends Model
{
    
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'like_house';

    protected $fillable = [
        'user_id', 'house_id'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }
}
