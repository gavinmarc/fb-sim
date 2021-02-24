<?php

namespace App\Http\Livewire;

use App\Models\League;
use App\Models\Season;
use Livewire\Component;

class LeagueTable extends Component
{
  public $table = [];

  public $season = null;

  protected $listeners = [
    'seasonChanged'
  ];

  public function mount()
  {
    $league = League::find(1);

    if (!$this->season) {
      $this->season = Season::currentSeason($league);
    }

    $this->table = $league->table($this->season);
  }

  public function render()
  {
    return view('livewire.league-table');
  }

  public function seasonChanged($selected)
  {
    dd($selected);

    $this->season = $selected;

    $this->table = $league->table($this->season);
  }
}
