<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('user_app', function ($table) {
            $table->increments('id')->unsigned();
            $table->integer('user_id');
            $table->integer('app_id');
            $table->timestamps();

            $table->index('user_id');
            $table->unique('app_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_app');
    }
}
