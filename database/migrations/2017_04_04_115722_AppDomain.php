<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AppDomain extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app', function(Blueprint $table) {
            $table->string('domain', 32)->after('secret');
            $table->index('domain');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app', function(Blueprint $table) {
            $table->dropIndex('domain');
            $table->dropIfExists('domain');
        });
    }
}
