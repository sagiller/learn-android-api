<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('owner_id'); //所有者ID外键
            $table->String('owner_type',50); //所有者是哪张标,比如user
            $table->String('type',50); //letter_cover letter_photos marries user_avatar
            $table->string('path',200); //图片保存的相对路径
            $table->timestamps(); //the time that platform been created
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('images');
    }
}
