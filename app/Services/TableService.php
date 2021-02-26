<?php

namespace App\Services;

use App\Models\League;
use App\Models\Season;
use App\Models\Table;
use App\Models\TableEntry;
use Facades\App\Services\StatisticService;

class TableService
{
  /**
   * Creates or updates table for the given league and season.
   * 
   * @param  League $league
   * @param  int|null $seasonId
   * @return Table
   */
  public function updateOrCreate(League $league, ?int $seasonId = null)
  {
    $seasonId = $seasonId ?? Season::currentSeason($league);

    $league->load(['teams.homeFixtures' => fn ($query) => $query->where('season_id', $seasonId)]);
    $league->load(['teams.awayFixtures' => fn ($query) => $query->where('season_id', $seasonId)]);

    $table = Table::firstOrCreate([
      'league_id' => $league->id,
      'season_id' => $seasonId,
    ]);

    $league->teams
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

    $table->update(['completed' => $league->isSeasonComplete($seasonId)]);
  }
}

  // public function table(int $season)
  // {
  //   $this->load('teams', 'teams.homeFixtures', 'teams.awayFixtures');

  //   return $this->teams
  //     ->map(function ($team) use ($currentSeason) {
  //       $teamStatistic = StatisticService::team($team, $currentSeason);
  //       return array_merge($team->attributesToArray(), $teamStatistic);
  //     })->sortBy([
  //       fn ($a, $b) => $b['pts'] <=> $a['pts'],
  //       fn ($a, $b) => $b['gd'] <=> $a['gd']
  //       // TODO: Error when gd = 0
  //       // fn ($a, $b) => $b['gd'] <=> $a['gd']
  //     ]);
  // }