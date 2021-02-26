<?php

namespace App\Services;

use App\Enums\LocationEnum;
use App\Enums\OutcomeEnum;
use App\Models\Season;
use App\Models\Result;
use App\Models\Team;
use App\Models\Fixture;
use Facades\App\Helper\Poisson;
use Illuminate\Support\Collection;

class MatchProbabilityService
{
  /** @var Collection */
  private $leagueFixtures;

  /** @var integer */
  private $season;

  /** @var integer */
  private $lastSeason;

  /**
   * Calculates poisson distribution for number of goals per team.
   * 
   * @param  Fixture $fixture 
   * @return array       
   */
  public function calculate(Fixture $fixture)
  {
    $homeTeam = $fixture->homeTeam;
    $awayTeam = $fixture->awayTeam;

    $this->season = $fixture->season_id;
    $this->leagueFixtures =  $this->loadFixtures($fixture, $this->season);

    // load last seasons fixtures, if not enough fixtures are completed 
    if (count($this->leagueFixtures) < 45) {
      $this->lastSeason = Season::lastSeason($fixture->league);
      $this->leagueFixtures = $this->leagueFixtures->merge(
        $this->loadFixtures($fixture, $this->lastSeason)
      );
    }

    $expectedHomeGoals = $this->expectedHomeGoals($homeTeam, $awayTeam);
    $expectedAwayGoals = $this->expectedAwayGoals($homeTeam, $awayTeam);

    return $this->resultProbibilities($expectedHomeGoals, $expectedAwayGoals);
  }

  /**
   * Loads league fixtures for the given season id.
   * 
   * @param  Fixture $fixture
   * @param  int $seasonId
   * @return Collection          
   */
  private function loadFixtures(Fixture $fixture, int $seasonId)
  {
    return Fixture::query()
      ->where('league_id', $fixture->league_id)
      ->completedForSeason($seasonId)
      ->get();
  }

  /**
   * Calculates probibilities for home team win, draw and away team win.
   * 
   * @param  Collection $results
   * @return Collection
   */
  public function cummulativeResultProbibilities(Collection $results)
  {
    $home = $results->where('outcome', OutcomeEnum::HOME)->sum('probability');
    $draw = $results->where('outcome', OutcomeEnum::DRAW)->sum('probability');
    $away = $results->where('outcome', OutcomeEnum::AWAY)->sum('probability');

    return collect(compact('home', 'draw', 'away'));
  }

  /**
   * Calculates match result probabilities.
   * 
   * @param  float $homeChance
   * @param  float $awayChance
   * @return Collection
   */
  private function resultProbibilities(float $homeChance, float $awayChance)
  {
    $occurrences = range(0, 5);

    $homeGoalsProb = [];
    $awayGoalsProb = [];

    foreach ($occurrences as $occurrence) {
      $homeGoalsProb[] = Poisson::poisson($homeChance, $occurrence);
      $awayGoalsProb[] = Poisson::poisson($awayChance, $occurrence);
    }

    $results = collect();
    foreach ($homeGoalsProb as $hGoals => $hProb) {
      foreach ($awayGoalsProb as $aGoals => $aProb) {
        $results->push(
          new Result($hGoals, $aGoals, $hProb * $aProb)
        );
      }
    }

    return $results;
  }

  /**
   * Calculates expected goals for the home team.
   * 
   * @param  Team   $homeTeam 
   * @param  Team   $awayTeam 
   * @return float           
   */
  private function expectedHomeGoals(Team $homeTeam, Team $awayTeam)
  {
    $homeTeamAttackStrength = $this->attackStrength($homeTeam, LocationEnum::HOME);
    $awayTeamDefensiveStrength = $this->defensiveStrength($awayTeam, LocationEnum::AWAY);
    $avgHomeLeagueGoals = $this->leagueFixtures->sum('home_team_goals') / $this->leagueFixtures->count();
    return $homeTeamAttackStrength * $awayTeamDefensiveStrength * $avgHomeLeagueGoals;
  }

  /**
   * Calculates expected goals for the away team.
   * 
   * @param  Team   $homeTeam 
   * @param  Team   $awayTeam 
   * @return float           
   */
  private function expectedAwayGoals(Team $homeTeam, Team $awayTeam)
  {
    $awayTeamAttackStrength = $this->attackStrength($awayTeam, LocationEnum::AWAY);
    $homeTeamDefensiveStrength = $this->defensiveStrength($homeTeam, LocationEnum::HOME);
    $avgAwayLeagueGoals = $this->leagueFixtures->sum('away_team_goals') / $this->leagueFixtures->count();
    return $awayTeamAttackStrength * $homeTeamDefensiveStrength * $avgAwayLeagueGoals;
  }

  /**
   * Calculates attack strength for the given team.
   * 
   * @param  Team $team 
   * @param  string $location 
   * @return float 
   */
  private function attackStrength(Team $team, string $location)
  {
    return $this->strength($team, $location, true);
  }  

  /**
   * Calculates defensive strength for the given team.
   * 
   * @param  Team $team 
   * @param  string $location 
   * @return float 
   */
  private function defensiveStrength(Team $team, string $location)
  {
    return $this->strength($team, $location, false);
  }  

  private function strength(Team $team, string $location, bool $forAttack = true)
  {
    $relation = "{$location}Fixtures";
    $teamFixtures = $team->$relation()->completedForSeason($this->season)->get();

    if (count($teamFixtures) < 5) {
      $teamFixtures = $teamFixtures->merge(
        $team->$relation()->completedForSeason($this->lastSeason)->get()
      );
    }

    $attribute = $location;
    if (!$forAttack) {
      $attribute = $location == LocationEnum::HOME ? LocationEnum::AWAY : LocationEnum::HOME;
    }

    $avgTeamGoals = $teamFixtures->sum("{$attribute}_team_goals") / $teamFixtures->count();
    $avgLeagueGoals = $this->leagueFixtures->sum("{$attribute}_team_goals") / $this->leagueFixtures->count();
    
    return $avgTeamGoals / $avgLeagueGoals;
  }
}