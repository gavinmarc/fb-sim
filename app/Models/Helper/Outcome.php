<?php

namespace App\Models\Helper;

use Arr;

class Outcome 
{
  /** @var float */
  public $home;

  /** @var float */
  public $draw;

  /** @var float */
  public $away;

  public function __construct(float $home, float $draw, float $away) 
  {
    $this->home = number_format($home, 5);
    $this->draw = number_format($draw, 5);
    $this->away = number_format($away, 5);
  }

  public static function make(array $data)
  {
    $home = (float) Arr::get($data, 'home', 0);
    $draw = (float) Arr::get($data, 'draw', 0);
    $away = (float) Arr::get($data, 'away', 0);

    return new self($home, $draw, $away);
  }

  public function toCollection()
  {
    return collect([
      'home' => $this->home,
      'draw' => $this->draw,
      'away' => $this->away,
    ]);
  }
}