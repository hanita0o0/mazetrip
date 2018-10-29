<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventphotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     * the photo model that is just for a events and include phtos for
     * gallery - posts - event header - timeline
     */
    public function up()
    {
        Schema::create('eventphotos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('path');
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
        Schema::dropIfExists('eventphotos');
    }
}
