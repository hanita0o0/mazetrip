<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('body')->nullable();
            $table->integer('avatar_id')->nullable();
            $table->timestamp('date')->nullable();
            $table->timestamp('end_date')->nullable();

            //event_id is the foreign between a ticket and event the relation is one to many
            // every event does have many tickets
            // nullable because in future we can add mini tickets for the one time event
            $table->integer('event_id')->nullable();



            //activation number will be used to show this number to users in case for report or something like
            $table->string('activation_num')->unique();


            $table->integer('price')->nullable();

            // state and city part will not be used in future and are reserved
            $table->string('state')->nullable();
            $table->string('city')->nullable();

            //address part will be hidden and required
            $table->string('address')->nullable();
            $table->boolean('is_active')->nullable();
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
        Schema::dropIfExists('tickets');
    }
}
