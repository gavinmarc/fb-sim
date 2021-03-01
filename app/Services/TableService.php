<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Season;
use App\Models\Table;
use App\Models\TableEntry;
use App\Models\Team;
use Facades\App\Services\StatisticService;

class TableService
{
  /**
   * Creates or updates table for the given season.
   * 
   * @param  int|null $seasonId
   * @return Table
   */
  public function updateOrCreate(?int $seasonId = null)
  {
    $seasonId = $seasonId ?? Season::currentSeason();

    $teams = Team::with(['homeFixtures', 'awayFixtures'])->get();
    
    $teams->load(['homeFixtures' => fn ($query) => $query->where('season_id', $seasonId)]);
    $teams->load(['awayFixtures' => fn ($query) => $query->where('season_id', $seasonId)]);

    $table = Table::firstOrCreate(['season_id' => $seasonId]);

    $teams
      ->map(fn ($team) => StatisticService::team($team, $seasonId))
      ->sortBy([
        fn ($a, $b) => $b['pts'] <=> $a['pts'],
        fn ($a, $b) => $b['gd'] <=> $a['gd']
      ])
      ->map(function ($statistics, $index) {
        $statistics['position'] = $index + 1;
        return $statistics;
      })
      ->each(function ($statistics) use ($table) {
        TableEntry::updateOrCreate([
          'team_id' => $statistics['team_id'],
          'table_id' => $table->id
        ], $statistics);
      });

    $table->update(['completed' => $this->isSeasonComplete($seasonId)]);
  }

  /**
   * Checks if all matches for the given season are completed.
   * 
   * @param  int $seasonId
   * @return boolean
   */
  private function isSeasonComplete(int $seasonId)
  {
    $playedMatches = Fixture::forSeason($seasonId)->count();
    $totalMatches = matchdays() * fixturesPerMatchday();
    return $playedMatches == $totalMatches;
  }
}
