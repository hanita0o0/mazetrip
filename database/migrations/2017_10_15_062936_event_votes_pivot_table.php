<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EventVotesPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * a pivot table to connect
     * events to votes
     * for votes about managers
     *
     * @return void
     */
    public function up()
    {
        Schema::create('event_vote', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('event_id');
            $table->integer('vote_id');
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
        Schema::dropIfExists('event_vote');
    }
}
