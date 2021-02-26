<?php

namespace App\Events;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MatchdayCompleted
{
  use Dispatchable, SerializesModels;

  public $fixtures;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(Collection $fixtures)
  {
    $this->fixtures = $fixtures;
  }
}
