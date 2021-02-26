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
      'seasons' => Season::orderBy('id', 'desc')->get()
    ]);
  }

  public function updatedSelected()
  {
    $this->emit('seasonChanged', $this->selected);
  }
}
