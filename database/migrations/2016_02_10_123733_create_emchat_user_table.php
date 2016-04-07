<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmchatUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emchat_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id'); //所有者ID外键
            $table->string('uuid');
            $table->string('type');
            $table->string('username')->unique();
            $table->string('nickname');
            $table->string('password', 60);
            $table->boolean('activated');
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
        Schema::drop('emchat_user');
    }
}
