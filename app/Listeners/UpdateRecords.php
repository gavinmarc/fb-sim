<?php

namespace App\Listeners;

use App\Events\MatchdayCompleted;
use Facades\App\Services\TableService;

class UpdateRecords
{
  /**
   * Handle the event.
   *
   * @param  MatchdayCompleted  $event
   * @return void
   */
  public function handle(MatchdayCompleted $event)
  {
    RecordService::updateOrCreate();
  }
}
