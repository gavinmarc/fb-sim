<?php

namespace App\Models;

class Result 
{
  public CONST HOME = 'home'; 
  public CONST DRAW = 'draw'; 
  public CONST AWAY = 'away'; 

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
    $this->outcome = $this->outcome();
  }

  private function outcome()
  {
    if ($this->homeGoals > $this->awayGoals) {
      return self::HOME;
    }

    if ($this->awayGoals > $this->homeGoals) {
      return self::AWAY;
    }

    return self::DRAW;
  }
}