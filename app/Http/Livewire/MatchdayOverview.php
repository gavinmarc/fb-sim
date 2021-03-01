<?php

namespace App\Http\Livewire;

use App\Models\Fixture;
use Livewire\Component;

class MatchdayOverview extends Component
{
  public $fixtures;

  public function mount()
  {
    $this->fixtures = Fixture::nextMatchday();
  }

  public function render()
  {
    return view('livewire.matchday-overview');
  }
}