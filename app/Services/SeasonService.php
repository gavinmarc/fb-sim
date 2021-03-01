<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Season;
use App\Models\Team;
use ScheduleBuilder;

class SeasonService
{
  /**
   * Creates a new season and generates all fixtures.
   * 
   * @param  boolean $force
   * @return Season
   */
  public function new(bool $force = false)
  {
    // check if the current season is complete
    $fixture = Fixture::query()
      ->with('season')
      ->notCompleted()
      ->orderBy('season_id', 'asc')
      ->orderBy('matchday', 'asc')
      ->first();

    if ($fixture && !$force) {
      return $fixture->season;
    }

    $season = $this->createSeason();

    $schedule = $this->createSchedule();

    $fixtures = $this->formatFixtures($schedule, $season);
    
    Fixtures::create($fixtures);

    return $season;
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
   * Creates a new schedule.
   * 
   * @return array
   */
  private function createSchedule()
  {
    $teams = Team::pluck('id')->toArray();

    return (new ScheduleBuilder($teams, matchdays()))->build()->full();
  }

  /**
   * Creates a new season. If the current seaosn has no fixtures,
   * the current season gets returned.
   * 
   * @return Season
   */
  private function createSeason()
  {
    $currentSeason = Season::currentSeason();

    // check if the current season has games attached
    if (Fixture::forSeason($currentSeason->id)->count() == 0) {
      return $currentSeason;
    }
    
    $startYear = (int) explode('/', $currentSeason->years)[1];
    $years = $startYear . '/'. ($startYear + 1);
    
    return Season::create(['years' => $years]);
  }

}