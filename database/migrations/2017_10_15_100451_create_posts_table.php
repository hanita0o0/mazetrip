<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * this table is a post for users :
     * ==> users can make posts related to events or related to themselves
     * relationships :
     * post->user as likes
     * post->user as maker
     * post->photos as visual content ( this photos should save as photo that belongs to user not event)
     * posts->comments as comments
     * posts->event as belongs to what event ( can be nullable )
     *
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body')->nullable();
            $table->integer('user_id');
            $table->integer('event_id')->nullable();
            $table->integer('media_id')->nullable();
            $table->softDeletes('deleted_at');
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
