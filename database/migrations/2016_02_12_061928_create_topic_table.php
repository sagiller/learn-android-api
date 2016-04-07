<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('topic', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('group_id');
            $table->integer('marry_id')->default(0);
            $table->integer('user_id');
            $table->integer('parent_id')->default(0); //主题为0，回复为对应的topic_id
            $table->integer('relate_id')->default(0); //关联主题
            $table->integer('to_user_id')->default(0);
            $table->string('to_username');
            $table->string('title');
            $table->text('content');
            $table->integer('star_count')->default(0);
            $table->integer('post_count')->default(0);
            $table->string('memo');
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
        //
    }
}
