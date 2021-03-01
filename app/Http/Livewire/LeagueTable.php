<?php

namespace App\Http\Livewire;

use App\Models\Season;
use App\Models\Table;
use Livewire\Component;

class LeagueTable extends Component
{
  public $table = [];

  public $season = null;

  public $full = true;

  protected $listeners = ['seasonChanged'];

  public function mount()
  {
    if (!$this->season) {
      $this->season = Season::currentSeason();
    }

    $this->loadTable();
  }

  private function loadTable()
  {
    $this->table = Table::query()
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
