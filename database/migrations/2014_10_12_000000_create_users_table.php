<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('is_admin')->default(0);//是否是管理员  0不是 1是
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('openid');
            $table->string('headimg');
            $table->smallInteger('sex');//用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
            $table->string('province');
            $table->string('city');
            //$table->tinyInteger('subscribe');//订阅否
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
        Schema::drop('users');
    }
}
