<?php

namespace App\Services;

use App\Enums\RecordTypeEnum;
use App\Models\Fixture;
use App\Models\Record;
use App\Models\Table;

class RecordService 
{ 
  /** @var Collection */
  private $tables;

  /** @var Collection */
  private $entries;

  /**
   * Updates or creates records.
   * 
   * @return 
   */
  public function updateOrCreate()
  {
    $records = collect();

    $this->tables = Table::query()
      ->with(['season', 'entries.team'])
      ->get();

    $this->entries = $this->tables->pluck('entries')->flatten();

    // team records
    $records->push($this->teamMostTitles());

    // season records
    $records->push($this->seasonMostGoals());

    // fixture records
    $records->push($this->fixtureHighestDraw());

    // delete all records and create new ones
    Record::truncate();

    Record::create($records);
  }  

  /** Team with the most titles all-time */
  private function teamMostTitles()
  {
    $type = RecordTypeEnum::TEAM;
    $title = 'Most titles (all-time)';

    $entries = $this->entries
      ->filter(fn ($entry) => $entry->position == 1)
      ->groupBy('team_id')
      ->sortByDesc(fn ($entry) => count($entry))
      ->first();

    $team = $entries->first()->team;
    $value = $entries->count();

    return compact('type', 'title', 'team', 'value');
  }

  /** Most goals in one season */
  private function seasonMostGoals()
  {
    $type = RecordTypeEnum::SEASON;
    $title = 'Most goal-scoring season';

    $table = $this->tables
      ->map(function ($table) {
        $table['goals'] = $table->entries->sum('gf');
        return $table;
      })
      ->sortByDesc('goals')
      ->first();

    $season = $table->season;
    $value = $table->goals;

    return compact('type', 'title', 'season', 'value');
  }

  /** Most goals in a match that ended with a draw */
  private function fixtureHighestDraw()
  {
    $type = RecordTypeEnum::FIXTURE;
    $title = 'Highest goal-scoring draw';

    $fixture = Fixture::query()
      ->selectRaw('*, home_team_goals + away_team_goals as goals')
      ->whereColumn('home_team_goals', 'away_team_goals')
      ->with('homeTeam', 'awayTeam')
      ->reorder()
      ->orderBy('goals', 'desc')
      ->first();

    $value = $fixture->goals;

    return compact('type', 'title', 'fixture', 'value');
  }
}