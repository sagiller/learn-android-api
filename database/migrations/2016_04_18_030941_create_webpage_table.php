<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebpageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webpage', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order'); //排序
            $table->string('name'); //name
            $table->string('url'); //url
            $table->integer('status');  //0:normal，-1：invisiable
            $table->integer('category_id');  //category_id
            $table->integer('type');  //0:is normal，1：is module
            $table->string('desc'); //简介
            $table->timestamps();
            //category
            //icon
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('webpage');
    }
}
