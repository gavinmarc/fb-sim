<?php

namespace App\Listeners;

use App\Events\MatchdayCompleted;
use Facades\App\Services\TableService;

class UpdateLeagueTable
{
  /**
   * Handle the event.
   *
   * @param  MatchdayCompleted  $event
   * @return void
   */
  public function handle(MatchdayCompleted $event)
  {
    $fixture = $event->fixtures->first();

    TableService::updateOrCreate($fixture->league, $fixture->season_id);
  }
}
