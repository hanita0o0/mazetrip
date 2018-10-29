<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('header')->nullable();
            $table->text('about')->nullable();
            $table->text('about_team')->nullable();
            $table->boolean('is_active')->default(1);
            $table->string('activation_no',15)->unique();
            //avatar is a avatar image

            $table->integer('avatar')->nullable();

            //this below (event-profile-image) is for the header image and has nothing to do with gallery
            //let it seprate
            $table->integer('state')->nullable();
            $table->integer('city')->nullable();

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
        Schema::dropIfExists('events');
    }
}
