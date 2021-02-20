<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Seeder;

class LeagueSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // create league
    $league = League::factory()->create([
      'name' => 'Bundesliga',
      'country_code' => 'de',
      'tier' => 1
    ]);

    $teamspath = file_get_contents(database_path('assets/teams.json'));
    $teams = json_decode($teamspath, true);

    collect($teams['bundesliga'])
      ->each(function ($name, $abbr) use ($league) {
        Team::factory()
          ->for($league)
          ->create(['name' => $name, 'short_name' => $abbr]);
      });
    
  }
}
