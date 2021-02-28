<?php

namespace App\Http\Livewire;

use App\Models\Season;
use Livewire\Component;

class SeasonSelect extends Component
{
  public $selected;

  public $seasons;

  public function mount()
  {
    $this->seasons = Season::orderBy('id', 'desc')->get();

    $this->selected = $this->seasons->first()->id;
  }

  public function render()
  {
    return view('livewire.season-select');
  }

  public function updatedSelected()
  {
    $this->emit('seasonChanged', $this->selected);
  }
}
