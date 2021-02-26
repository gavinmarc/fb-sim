<?php

namespace App\Providers;

use App\Events\MatchdayCompleted;
use App\Listeners\UpdateLeagueTable;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    MatchdayCompleted::class => [
      UpdateLeagueTable::class,
    ],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot()
  {
    //
  }
}
