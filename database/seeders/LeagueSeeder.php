<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class LeagueSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    // create season 
    Season::factory()->create(['years' => '2020/2021']);

    // create league
    $league = League::factory()->create([
      'name' => 'Bundesliga',
      'country_code' => 'de',
      'tier' => 1
    ]);

    // read teams json
    $teamspath = file_get_contents(database_path('assets/teams.json'));
    $teams = json_decode($teamspath, true);

    // create teams
    collect($teams['bundesliga'])
      ->each(function ($name, $abbr) use ($league) {
        Team::factory()
          ->for($league)
          ->create(['name' => $name, 'short_name' => $abbr]);
      });
    
    // import fixtures 
    Artisan::call('fixtures:import', [
      '--league' => 'Bundesliga', 
      '--season' => 1,
      '--file' => database_path('assets/bundesliga_fixtures_20-21.csv')
    ]);
  }
}
