<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'collection';

    protected $fillable = [
        'user_id', 'house_id'
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
