<?php

namespace Database\Factories;

use App\Models\TableEntry;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

class TableEntryFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = TableEntry::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'team_id' => Team::factory(),
      'postion' => $this->faker->numberBetween(1, 18),
      'mp' => $this->faker->numberBetween(1, 34),
      'w' => $this->faker->numberBetween(1, 34),
      'd' => $this->faker->numberBetween(1, 34),
      'l' => $this->faker->numberBetween(1, 34),
      'gf' => $this->faker->numberBetween(1, 100),
      'ga' => $this->faker->numberBetween(1, 100),
      'gd' => $this->faker->numberBetween(-50, 50),
      'pts' => $this->faker->numberBetween(0, 102),
    ];
  }
}
