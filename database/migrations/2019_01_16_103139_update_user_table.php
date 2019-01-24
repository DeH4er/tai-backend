<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('users', function (Blueprint $table) {
        $table->integer('dict_id')
          ->unsigned()
          ->nullable();
        $table->foreign('dict_id')
          ->references('id')
          ->on('dict')
          ->onDelete('cascade');

        $table->integer('stats_id')
          ->unsigned()
          ->nullable();
        $table->foreign('stats_id')
          ->references('id')
          ->on('stats')
          ->onDelete('cascade');

        $table->integer('level')
          ->default('1');
      });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('dict_id');
        $table->dropColumn('stats_id');
        $table->dropColumn('level');
      });
    }
}
