<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('tables', function (Blueprint $table) {
      $table->id();
      $table->foreignId('season_id')->constrained();
      $table->foreignId('league_id')->constrained();
      $table->boolean('completed')->default(false);
      $table->timestamps();
    });

    Schema::create('table_entries', function (Blueprint $table) {
      $table->id();
      $table->foreignId('table_id')->constrained();
      $table->foreignId('team_id')->constrained();
      $table->unsignedInteger('position');
      $table->unsignedInteger('mp');
      $table->unsignedInteger('w');
      $table->unsignedInteger('d');
      $table->unsignedInteger('l');
      $table->unsignedInteger('gf');
      $table->unsignedInteger('ga');
      $table->integer('gd');
      $table->unsignedInteger('pts');
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
    Schema::dropIfExists('table_entries');
    Schema::dropIfExists('tables');
  }
}
