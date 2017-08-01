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
            $table->string('post_name')->default('Post');
            $table->text('post_message')->nullable();
            $table->text('link')->nullable();
            $table->text('media')->nullable();
            $table->integer('likes')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('shares')->default(0);
            $table->integer('internal_likes')->default(0);
            $table->integer('internal_comments')->default(0);
            $table->integer('internal_shares')->default(0);
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
