<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Record;
use App\Models\Table;
use App\Models\Team;

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
  public function create()
  {
    $records = collect();

    $this->tables = Table::query()
      ->with('entries.team')
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

    $records->each(fn ($data) => Record::create($data));
  }  

  /** Team with the most titles all-time */
  private function teamMostTitles()
  {
    $title = 'Most titles (all-time)';

    $entries = $this->entries
      ->filter(fn ($entry) => $entry->position == 1)
      ->groupBy('team_id')
      ->sortByDesc(fn ($entry) => count($entry))
      ->first();

    $recordable_id = $entries->first()->team->id;
    $recordable_type = Team::class;
    
    $value = $entries->count();

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  /** Most goals in one season */
  private function seasonMostGoals()
  {
    $title = 'Most goal-scoring season';

    $table = $this->tables
      ->map(function ($table) {
        $table['goals'] = $table->entries->sum('gf');
        return $table;
      })
      ->sortByDesc('goals')
      ->first();

    $recordable_id = $table->season_id;
    $recordable_type = Season::class;

    $value = $table->goals;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  /** Most goals in a match that ended with a draw */
  private function fixtureHighestDraw()
  {
    $title = 'Highest goal-scoring draw';

    $fixture = Fixture::query()
      ->selectRaw('*, home_team_goals + away_team_goals as goals')
      ->whereColumn('home_team_goals', 'away_team_goals')
      ->with('homeTeam', 'awayTeam')
      ->reorder()
      ->orderBy('goals', 'desc')
      ->first();

    $recordable_id = $fixture->id;
    $recordable_type = Fixture::class;

    $value = $fixture->goals;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }
}