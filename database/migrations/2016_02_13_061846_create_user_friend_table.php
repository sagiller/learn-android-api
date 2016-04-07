<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserFriendTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_friend', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id'); //所有者ID外键
            $table->string('friend_user_id');
            $table->integer('status');  //0:申请中，1：已同意(final status)
            $table->string('comment'); //申请理由
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
        Schema::drop('user_friend');
    }
}
