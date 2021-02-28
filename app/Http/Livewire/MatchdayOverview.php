<?php

namespace App\Http\Livewire;

use App\Models\League;
use Livewire\Component;

class MatchdayOverview extends Component
{
  public $league;

  public $fixtures;

  public function mount()
  {
    $this->league = League::find(1);

    $this->fixtures = $this->league->nextMatchdayFixtures();
  }

  public function render()
  {
    return view('livewire.matchday-overview');
  }
}