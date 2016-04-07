<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLettersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('letters', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('from_user_id'); //发送者用户ID
            $table->integer('to_user_id'); //接收者用户ID
            $table->string('to_nickname',100); //to的称呼
            $table->string('from_nickname',100); //from的称呼
            $table->string('content',2000); // 正文
            $table->string('tag',200); //纪念日
            $table->date('tag_date'); //纪念日日期
            $table->string('security_code',50); //传情密语
            $table->string('qr_code_content',100)->unique(); //供生成二维码的内容，客户端生成的随机串
            $table->integer('status'); //0刚生成 1已经被阅读（绑定了接收者用户ID）
            $table->timestamps(); //the time that platform been created
            $table->softDeletes(); //soft delete
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('letters');
    }
}
