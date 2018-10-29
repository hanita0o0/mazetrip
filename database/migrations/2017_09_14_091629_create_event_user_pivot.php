<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventUserPivot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    //this is a pivot table for user to make a event their favorite and get notifications by
    // and by default should have it on and if after 5 times user didnt reply to notification should deatach it
    public function up()
    {
        Schema::create('event_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('user_id');
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
        Schema::dropIfExists('event_user');
    }
}
