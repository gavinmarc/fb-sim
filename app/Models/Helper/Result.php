<?php

namespace App\Models\Helper;

use App\Enums\OutcomeEnum;
use App\Helper\Outcome;
use Arr;

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

  public function __construct(int $homeGoals, int $awayGoals, float $probability, string $outcome = '') 
  {
    $this->homeGoals = $homeGoals;
    $this->awayGoals = $awayGoals;
    $this->probability = number_format($probability, 5);
    $this->outcome = in_array($outcome, OutcomeEnum::values())
      ? $outcome
      : Outcome::get($homeGoals, $awayGoals);
  }

  public static function make(array $data)
  {
    $homeGoals = (int) Arr::get($data, 'homeGoals', 0);
    $awayGoals = (int) Arr::get($data, 'awayGoals', 0);
    $probability = (float) Arr::get($data, 'probability', 0);
    $outcome = Arr::get($data, 'outcome', '');

    return new self($homeGoals, $awayGoals, $probability, $outcome);
  }

  public function goals()
  {
    return $this->homeGoals + $this->awayGoals;
  }
}