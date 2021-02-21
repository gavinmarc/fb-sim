<?php

namespace App\Models;

use App\Enums\OutcomeEnum;
use App\Helper\Outcome;

class Result 
{
  /** @var int */
  public $homeGoals;

  /** @var int */
  public $awayGoals;

  /** @var float */
  public $probability;

  /** @var string */
  public $outcome;

  public function __construct(int $homeGoals, int $awayGoals, float $probability) 
  {
    $this->homeGoals = $homeGoals;
    $this->awayGoals = $awayGoals;
    $this->probability = number_format($probability, 3);
    $this->outcome = Outcome::get($homeGoals, $awayGoals);
  }
}