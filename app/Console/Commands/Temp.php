<?php

namespace App\Console\Commands;

use App\Models\Team;
use Facades\App\Helper\Poisson;
use Facades\App\Services\MatchProbabilityService;
use Illuminate\Console\Command;

class Temp extends Command
{
  protected $signature = 'temp';

  public function handle()
  {
    $bayern = Team::find(8);
    $schalke = Team::find(14);

    MatchProbabilityService::calculate($bayern, $schalke);
  }
}
