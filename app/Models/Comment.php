<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'comments';

    protected $fillable = [
        'user_id', 'house_id', 'comment','like'
    ];

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function house()
    {
        return $this->belongsTo('App\Models\House','house_id');
    }
}
