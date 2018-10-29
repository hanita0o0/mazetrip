<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filterable_id');
            $table->string('filterable_type');
            $table->boolean('gender')->nullable();
            $table->integer('limit')->nullable();
            $table->boolean('request')->nullable();
            $table->boolean('hide_event')->nullable();
            /*
             * when request = 1 means the event does have a request and should attack to the request model
             * when request = 0 or null means there is no request required
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
        Schema::dropIfExists('filters');
    }
}
