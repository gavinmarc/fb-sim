<?php

namespace App\Http\Livewire;

use App\Models\League;
use App\Models\Season;
use Livewire\Component;

class LeagueTable extends Component
{
  public $league;

  public $table = [];

  public $season = null;

  protected $listeners = ['seasonChanged'];

  public function mount()
  {
    $this->league = League::find(1);

    if (!$this->season) {
      $this->season = Season::currentSeason($this->league);
    }

    $this->loadTable();
  }

  private function loadTable()
  {
    $this->table = $this->league
      ->tables()
      ->where('season_id', $this->season)
      ->with(['entries', 'entries.team'])
      ->first();
  }

  public function render()
  {
    return view('livewire.league-table');
  }

  public function seasonChanged($selected)
  {
    $this->season = $selected;

    $this->loadTable();
  }
}
