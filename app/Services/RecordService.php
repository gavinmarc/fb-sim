<?php

namespace App\Services;

use App\Models\Fixture;
use App\Models\Record;
use App\Models\Season;
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
   * INFO: Layout looks best, if the number of records is dividable by 6.
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

    // fixture records
    $records->push($this->fixtureHighestDraw());
    $records->push($this->fixtureHighestScoring());
    $records->push($this->fixtureMostHomeGoals());
    $records->push($this->fixtureMostAwayGoals());
    $records->push($this->fixtureMostGoalsInLoss());

    // team records
    $records->push($this->teamMostTitles());
    $records->push($this->teamMostGoals());
    $records->push($this->teamLeastGoals());
    $records->push($this->teamMostWins());
    $records->push($this->teamMostDraws());
    $records->push($this->teamMostLosses());

    // season records
    $records->push($this->seasonMostGoals());
    $records->push($this->seasonLeastGoals());

    // delete all records and create new ones
    Record::truncate();

    $records->each(fn ($data) => Record::create($data));
  }  

   //======================================================================
  // FIXTURE
  //======================================================================

  private function fixtureHighestDraw()
  {
    $title = 'Highest goal-scoring draw';

    $fixture = Fixture::query()
      ->selectRaw('*, home_team_goals + away_team_goals as goals')
      ->whereColumn('home_team_goals', 'away_team_goals')
      ->reorder()
      ->orderBy('goals', 'desc')
      ->first();

    $recordable_id = $fixture->id;
    $recordable_type = Fixture::class;

    $value = $fixture->goals;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  private function fixtureHighestScoring()
  {
    $title = 'Highest scoring game';

    $fixture = Fixture::query()
      ->selectRaw('*, home_team_goals + away_team_goals as goals')
      ->reorder()
      ->orderBy('goals', 'desc')
      ->first();

    $recordable_id = $fixture->id;
    $recordable_type = Fixture::class;

    $value = $fixture->goals;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  private function fixtureMostHomeGoals()
  {
    return $this->fixtureMostGoals('home');
  }

  private function fixtureMostAwayGoals()
  {
    return $this->fixtureMostGoals('away');
  }

  private function fixtureMostGoals(string $type)
  {
    $title = "Highest scring $type team";

    $attr = "{$type}_team_goals";
    $fixture = Fixture::query()
      ->orderBy($attr, 'desc')
      ->first();

    $recordable_id = $fixture->id;
    $recordable_type = Fixture::class;

    $value = $fixture->$attr;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  private function fixtureMostGoalsInLoss()
  {
    $title = 'Most goals in a loss';

    $home = Fixture::query()
      ->whereRaw('home_team_goals < away_team_goals')
      ->orderBy('home_team_goals', 'desc')
      ->first();

    $away = Fixture::query()
      ->whereRaw('away_team_goals < home_team_goals')
      ->orderBy('away_team_goals', 'desc')
      ->first();

    $homeGreater = $home->home_team_goals > $away->away_team_goals;
    
    $recordable_id = $homeGreater ? $home->id : $away->id;
    $recordable_type = Fixture::class;

    $value = $homeGreater ? $home->home_team_goals : $away->away_team_goals;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  //======================================================================
  // TEAM
  //======================================================================

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

  private function teamMostGoals() 
  {
    return $this->teamGoals(true);
  }

  private function teamLeastGoals() 
  {
    return $this->teamGoals(false);
  }

  private function teamGoals(bool $mostGoals)
  {
    $title = $mostGoals ? 'Most' : 'Least'; 
    $title .= ' goals (all-time)';

    $sortBy = $mostGoals ? 'sortDesc' : 'sort';
    $entries = $this->entries
      ->groupBy('team_id')
      ->mapWithKeys(fn ($data, $key) => [$key => $data->sum('gf')])
      ->$sortBy();

    $recordable_id = $entries->keys()->first();
    $recordable_type = Team::class;

    $value = $entries->first();

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }

  private function teamMostWins()
  {
    return $this->teamMostOutcome('win');
  }

  private function teamMostDraws()
  {
    return $this->teamMostOutcome('draw');
  }

  private function teamMostLosses()
  {
    return $this->teamMostOutcome('loss');
  }

  private function teamMostOutcome(string $type)
  {
    $title = 'Most ' . \Str::plural($type) . ' (all-time)';

    $entries = $this->entries
      ->groupBy('team_id')
      ->mapWithKeys(fn ($data, $key) => [$key => $data->sum($type[0])])
      ->sortDesc();

    $recordable_id = $entries->keys()->first();
    $recordable_type = Team::class;

    $value = $entries->first();

    return compact('recordable_type', 'recordable_id', 'title', 'value'); 
  }

  //======================================================================
  // SEASON
  //======================================================================

  private function seasonMostGoals()
  {
    return $this->seasonGoals(true);
  }

  private function seasonLeastGoals()
  {
    return $this->seasonGoals(false);
  }

  private function seasonGoals(bool $mostGoals)
  {
    $title = $mostGoals ? 'Most' : 'Least';
    $title .= ' goal-scoring season';

    $sortBy = $mostGoals ? 'sortByDesc' : 'sortBy';
    $table = $this->tables
      ->where('season_id', '!=', Season::currentSeason())
      ->map(function ($table) {
        $table['goals'] = $table->entries->sum('gf');
        return $table;
      })
      ->$sortBy('goals')
      ->first();

    $recordable_id = $table->season_id;
    $recordable_type = Season::class;

    $value = $table->goals;

    return compact('recordable_type', 'recordable_id', 'title', 'value');
  }
}