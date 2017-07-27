<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTwitterPosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('twitter_handle_id');
            $table->string('post_name')->nullable();
            $table->string('twitter_id');
            $table->text('post_message')->nullable();
            $table->text('link')->nullable();
            $table->text('media')->nullable();
            $table->string('hashtags')->nullable();
            $table->string('mentions')->nullable();
            $table->integer('favourites')->default(0);
            $table->integer('comments')->default(0);
            $table->integer('retweets')->default(0);
            $table->integer('internal_favourites')->default(0);
            $table->integer('internal_comments')->default(0);
            $table->integer('internal_retweets')->default(0);
            $table->timestamp('created_time');
            $table->foreign('twitter_handle_id')->references('id')->on('twitter_handles')->onDelete('cascade');
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
        Schema::dropIfExists('twitter_posts');
    }
}
