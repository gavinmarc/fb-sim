<?php

namespace App\Services;

use App\Models\League;
use App\Models\Season;
use ScheduleBuilder;

class SeasonService
{
  /**
   * Creates a new season for the given league and 
   * generates all fixtures.
   * 
   * @param  League $league
   * @param  boolean $force
   * @return void
   */
  public function new(League $league, bool $force = false)
  {
    // check if the league still has fixtures to play
    if ($league->fixtures()->notCompleted()->exists() && !$force) {
      throw new \Exception("League {$league->id} has still fixtures left.");
    }

    $season = $this->createSeason($league);

    $schedule = $this->createSchedule($league);

    $fixtures = $this->formatFixtures($schedule, $season);
    
    $league->fixtures()->createMany($fixtures);
  }

  /**
   * Formats the given schedule for mass insertion.
   * 
   * @param  array $schedule
   * @param  Season $season
   * @return Collection
   */
  private function formatFixtures(array $schedule, Season $season)
  {
    return collect($schedule)->flatMap(function ($item, $md) use ($season) {
      return collect($item)->map(function ($item) use ($md, $season) {
        return [
          'season_id' => $season->id,
          'matchday' => $md,
          'home_team_id' => $item[0],
          'away_team_id' => $item[1],
        ];
      });
    });
  }

  /**
   * Creates a new schedule for the given league.
   * 
   * @param  League $league
   * @return array
   */
  private function createSchedule(League $league)
  {
    $teams = $league->teams->pluck('id')->toArray();
    $rounds = $league->teams->count() * 2;

    return (new ScheduleBuilder($teams, $rounds))->build()->full();
  }

  /**
   * Creates a new season. If the league has no fixtures for the current season,
   * the current season gets returned.
   * 
   * @param  League $league
   * @return Season
   */
  private function createSeason(League $league)
  {
    $currentSeason = Season::latest()->first();

    // check if the current season has games attached
    if ($league->fixtures()->forSeason($currentSeason->id)->count() == 0) {
      return $currentSeason;
    }
    
    $startYear = (int) explode('/', $currentSeason->years)[1];
    $years = $startYear . '/'. ($startYear + 1);
    
    return Season::create(['years' => $years]);
  }

}