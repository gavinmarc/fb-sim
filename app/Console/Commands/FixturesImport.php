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

    foreach ($this->fixtures as $i => $fixture) {
      $homeTeam = $this->findTeam($fixture['home_team']);
      $awayTeam = $this->findTeam($fixture['away_team']);
      $homeGoals = strlen($fixture['home_team_goals']) == 0 ? null : (int) $fixture['home_team_goals'];
      $awayGoals = strlen($fixture['away_team_goals']) == 0 ? null : (int) $fixture['away_team_goals'];
      $this->createOrUpdateFixture(
        $fixture['matchday'], $homeTeam, $awayTeam, $homeGoals, $awayGoals
      );
    }

    return 0;
  }

  /**
   * Creates or updates fixture for the given teams.
   * 
   * @param  int $matchday 
   * @param  Team $hTeam    
   * @param  Team $aTeam    
   * @param  int|null $hGoals   
   * @param  int|null $aGoals   
   * @return void    
   */
  private function createOrUpdateFixture(int $matchday, Team $hTeam, Team $aTeam, ?int $hGoals, ?int $aGoals)
  {
    Fixture::updateOrCreate([
      'league_id' => $this->league->id, 
      'season_id' => $this->season->id, 
      'home_team_id' => $hTeam->id, 
      'away_team_id' => $aTeam->id,
      'matchday' => $matchday
    ], [
      'home_team_goals' => $hGoals,
      'away_team_goals' => $aGoals
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
          'matchday' => $columns->pull(0),
          'home_team' => $columns->pull(2),
          'away_team' => $columns->pull(5),
          'home_team_goals' => $columns->pull(3),
          'away_team_goals' => $columns->pull(4),
        ];
      });
  }
}
