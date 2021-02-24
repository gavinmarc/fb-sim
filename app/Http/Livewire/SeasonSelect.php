<?php

namespace App\Http\Livewire;

use App\Models\Season;
use Livewire\Component;

class SeasonSelect extends Component
{
  public $selected;

  public function render()
  {
    return view('livewire.season-select', [
      'seasons' => Season::all()
    ]);
  }

  public function updatedSelected()
  {
    $this->emit('seasonChanged', $this->selected);
  }
}
