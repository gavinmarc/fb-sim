<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFixtureProbabilitiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('fixture_probabilities', function (Blueprint $table) {
      $table->id();
      $table->foreignId('fixture_id')->constrained();
      $table->json('outcome');
      $table->json('over_under');
      $table->json('results');
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
    Schema::dropIfExists('fixture_probabilities');
  }
}
