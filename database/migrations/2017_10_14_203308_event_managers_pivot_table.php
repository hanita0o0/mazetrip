<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventManagersPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     *
     *
     * a pivot table to connect
     * event to users
     * as TOUR_MANAGERS
     *
     *
     *
     *
     * @return void
     */
    public function up()
    {
        Schema::create('touremanagers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('event_id');
//            $table->string('role')->nullable();
//            $table->string('about')->nullable();
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
        Schema::dropIfExists('touremanagers');
    }
}
