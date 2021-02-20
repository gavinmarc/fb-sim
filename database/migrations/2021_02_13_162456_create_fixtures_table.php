<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixturesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('fixtures', function (Blueprint $table) {
      $table->id();
      $table->foreignId('season_id')->constrained();
      $table->foreignId('league_id')->constrained();
      $table->unsignedInteger('matchday');
      $table->foreignId('home_team_id')->constrained('teams');
      $table->foreignId('away_team_id')->constrained('teams');
      $table->unsignedInteger('home_team_goals');
      $table->unsignedInteger('away_team_goals');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('fixtures');
  }
}
