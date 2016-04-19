<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebpageCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webpage_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order'); //排序
            $table->string('name'); //name
            $table->integer('type');  //0:is normal，1：is module
            $table->string('desc'); //简介
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
        Schema::drop('webpage_category');
    }
}
