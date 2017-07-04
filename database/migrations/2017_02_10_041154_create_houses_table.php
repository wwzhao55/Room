<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id') ->references('id')->on('users');
            $table->string('name',100);//名字
            $table->string('door',20)->nullable();//门牌号
            $table->string('province',100);//省
            $table->string('city',100);//市
            $table->string('district',100);//区
            $table->string('address');//地址
            $table->float('rent');//租金
            $table->integer('area');
            $table->foreign('area') ->references('id')->on('house_area');//面积
            $table->integer('type');
            $table->foreign('type') ->references('id')->on('house_type');//户型
            $table->string('orientation');//朝向
            $table->decimal('latitude',10,6);//纬度
            $table->decimal('longitude',10,6);//经度
            $table->text('good_text')->nullable();//点赞
            $table->text('bad_text')->nullable();//吐槽
            $table->smallInteger('score');//评分
           // $table->text('main_img');//主图
            $table->text('good_img')->nullable();//点赞的图
            $table->text('bad_img')->nullable();//吐槽的图
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('houses');
    }
}
