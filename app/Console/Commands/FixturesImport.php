<?php

namespace App\Console\Commands;

use App\Models\Fixture;
use App\Models\League;
use App\Models\Season;
use App\Models\Team;
use Illuminate\Console\Command;

class FixturesImport extends Command
{
  protected $signature = 'fixtures:import {--league=} {--season=} {--file=}';

  protected $description = 'Imports fixtures from the given file.';

  /** @var array */
  private $fixtures = null;

  /** @var League */
  private $league = null;

  /** @var Season */
  private $season = null;

  /** @var Collection */
  private $team = null;

  public function handle()
  {
    $this->handleInput();

    $fixturesPerMatchday = $this->league->fixtures_per_matchday;
    foreach ($this->fixtures as $i => $fixture) {
      $matchday = (int) ($i / $fixturesPerMatchday) + 1;
      $homeTeam = $this->findTeam($fixture['home_team']);
      $awayTeam = $this->findTeam($fixture['away_team']);
      $this->createOrUpdateFixture(
        $matchday, $homeTeam, $awayTeam, $fixture['home_team_goals'], $fixture['away_team_goals']
      );
    }

    return 0;
  }

  /**
   * Creates or updates fixture for the given teams.
   * 
   * @param  int    $matchday 
   * @param  Team   $hTeam    
   * @param  Team   $aTeam    
   * @param  int    $hGoals   
   * @param  int    $aGoals   
   * @return void    
   */
  private function createOrUpdateFixture(int $matchday, Team $hTeam, Team $aTeam, int $hGoals, int $aGoals)
  {
    Fixture::updateOrCreate([
      'league_id' => $this->league->id, 
      'season_id' => $this->season->id, 
      'home_team_id' => $hTeam->id, 
      'away_team_id' => $aTeam->id,
      'matchday' => $matchday
    ], [
      'home_team_goals' => $hGoals,
      'away_team_goals' => $aGoals,
    ]);
  }

  /**
   * Returns the team with the most similar name.
   *  
   * @param  string $name 
   * @return Team
   */
  private function findTeam(string $name)
  {
    $matchedTeam = null;
    $score = 0;

    foreach ($this->teams as $team) {
      similar_text($name, $team->name, $perc);
      if ($perc > $score) {
        $score = $perc;
        $matchedTeam = $team;
      }
    }

    return $matchedTeam;
  }

  /**
   * Handles user input.
   * 
   * @return void
   */
  private function handleInput()
  {
    $this->league = League::where('name', $this->option('league'))->with('teams')->first();
    $this->teams = $this->league->teams;

    $this->season = Season::find($this->option('season', 1));

    $contents = file_get_contents($this->option('file'));
    $rows = \Str::of($contents)->explode("\r\n");
    $rows->shift();

    $this->fixtures = $rows->filter()
      ->map(function ($row, $key) {      
        $columns = \Str::of($row)->explode(',');
        return [
          'home_team' => $columns->pull(3),
          'away_team' => $columns->pull(4),
          'home_team_goals' => $columns->pull(5),
          'away_team_goals' => $columns->pull(6),
        ];
      });
  }
}
