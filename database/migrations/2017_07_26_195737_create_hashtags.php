<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHashtag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashtags', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('twitter_handle_id')->nullable();
            $table->string('name');
            $table->tinyInteger('flag')->default(1);
            $table->foreign('twitter_handle_id')->references('id')->on('twitter_handles')->onDelete('cascade');
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
        Schema::dropIfExists('hashtag');
    }
}
