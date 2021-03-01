<?php

namespace App\Models\Helper;

use Arr;

class OverUnder 
{
  /** @var float */
  public $over15;

  /** @var float */
  public $under15;

  /** @var float */
  public $over25;

  /** @var float */
  public $under25;

  /** @var float */
  public $over35;

  /** @var float */
  public $under35;

  public function __construct(array $data) 
  {
    $this->over15 = number_format((float) Arr::get($data, 'over15', 0), 5);
    $this->under15 = number_format((float) Arr::get($data, 'under15', 0), 5);
    $this->over25 = number_format((float) Arr::get($data, 'over25', 0), 5);
    $this->under25 = number_format((float) Arr::get($data, 'under25', 0), 5);
    $this->over35 = number_format((float) Arr::get($data, 'over35', 0), 5);
    $this->under35 = number_format((float) Arr::get($data, 'under35', 0), 5);
  }
}