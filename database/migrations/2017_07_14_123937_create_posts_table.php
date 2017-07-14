<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('page_id');
            $table->string('fb_post_id');
            $table->text('post_message');
            $table->integer('likes');
            $table->integer('comments');
            $table->integer('shares');
            $table->integer('internal_likes');
            $table->integer('internal_comments');
            $table->integer('internal_shares');
            $table->timestamp('created_time');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->tinyInteger('flag')->default(1);
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
        Schema::dropIfExists('posts');
    }
}
