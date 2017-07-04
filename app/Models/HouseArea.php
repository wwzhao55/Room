<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseArea extends Model
{
    
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'house_area';

    protected $fillable = [
        'area'
    ];
}
