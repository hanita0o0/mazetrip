<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            Schema::defaultStringLength(191);
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('name_header',30);
            $table->string('email')->unique();
            $table->string('password');
            $table->string('activation_no')->unique()->nullable();
            $table->integer('state_id');
            $table->integer('city_id');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('gender');
            $table->text('bio')->nullable();
          
            $table->integer('avatar_id')->nullable();
            $table->integer('cover_id')->nullable();
            $table->string('api_token',60)->unique();
            $table->boolean('show_clubs')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
