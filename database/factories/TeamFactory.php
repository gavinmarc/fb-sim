<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TeamFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Team::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'league_id' => League::factory(),
      'name' => $this->faker->name,
      'short_name' => $this->faker->unique()->regexify('[a-z0-9]{3}')
    ];
  }
}
