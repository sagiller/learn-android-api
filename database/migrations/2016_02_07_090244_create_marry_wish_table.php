<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarryWishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marry_wish', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id'); //发布者user id
            $table->integer('marry_id'); //marry id
            $table->string('from_name'); //发布者nickname
            $table->string('content'); //内容
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
        Schema::drop('marry_wish');
    }
}
