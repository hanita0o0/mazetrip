<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableLocateComments extends Migration
{
    /**
     * Run the migrations.php
     *
     * @return void
     */
    public function up()
    {
        Schema::create('locateComments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('body');
            $table->string('author');
            $table->integer('star')->length(5)->nullable();
            $table->integer('location_id');
            $table->timestamps();
        });
    }

    /**a
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('locateComments');
    }
}
