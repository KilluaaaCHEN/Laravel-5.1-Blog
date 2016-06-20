<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post', function ($table) {
            $table->increments('post_id')->unsigned();
            $table->string('title', 100)->comment('标题');
            $table->string('tags', 100)->comment('标签以,分割');
            $table->string('desc')->comment('描述');
            $table->text('content')->comment('内容markdown语法');
            $table->smallInteger('state_id')->comment('状态 10启用,20停用,30草稿')->default(10);
            $table->integer('read_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('tag_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create('tag', function ($table) {
            $table->increments('tag_id')->unsigned();
            $table->string('tag_name', 50)->comment('名称');
            $table->integer('post_count')->coment('文章数');
        });
        Schema::create('post_tag', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('tag_id')->unsigned();
            $table->integer('post_id')->unsigned();
            $table->foreign('tag_id')->references('tag_id')->on('tag');
            $table->foreign('post_id')->references('post_id')->on('post');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post');
        Schema::drop('tag');
        Schema::drop('post_tag');
    }
}
