<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTwitterActions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('twitter_user_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('twitter_user_id');
            $table->unsignedInteger('twitter_post_id');
            $table->string('action_id')->nullable();
            $table->string('action_parent_id')->default(0);
            $table->enum('action', ['favourite', 'comment', 'retweet', 'hashtag', 'mention']);
            $table->text('details')->nullable();
            $table->string('action_perform')->nullable();
            $table->foreign('twitter_user_id')->references('id')->on('twitter_user_handles')->onDelete('cascade');
            $table->foreign('twitter_post_id')->references('id')->on('twitter_posts')->onDelete('cascade');
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
        Schema::dropIfExists('twitter_user_actions');
    }
}
