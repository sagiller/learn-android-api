<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMarryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marry', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer("user_id"); //创建者的用户ID
            $table->integer("groom_user_id"); //新郎对应的用户ID
            $table->integer("bride_user_id"); //新娘对应的用户ID
            $table->String('groom_name',50); //新郎姓名
            $table->String('bride_name',50); //新娘姓名
            $table->String('remarks',50); //寄语
            $table->string('security_code',50); //密语
            $table->string('qr_code_content',100)->unique(); //供生成二维码的内容，客户端生成的随机串
            $table->integer('status'); //0刚生成
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
        Schema::drop('marry');
    }
}
