<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CaptchaApp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('app', function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('key');
            $table->string('secret');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique('name');
            $table->unique('key');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('app');
    }
}
