<?php

namespace Database\Factories;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class FixtureFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Fixture::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'season_id' => Season::factory(),
      'league_id' => League::factory(),
      'home_team_id' => Team::factory(),
      'away_team_id' => Team::factory(),
      'matchday' => $this->faker->numberBetween(1, 34),
      'home_team_goals' => $this->faker->numberBetween(0, 5),
      'away_team_goals' => $this->faker->numberBetween(0, 5),
    ];
  }
}
