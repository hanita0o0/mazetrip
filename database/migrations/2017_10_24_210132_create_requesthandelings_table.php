<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequesthandelingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requesthandelings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('filter_id');
            $table->smallInteger('status');
            /*
             * when status =  0 means need approval
             * when status =  1 means the request rejected
             * when status =  2 means the request gets approval
             */
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
        Schema::dropIfExists('requesthandelings');
    }
}
