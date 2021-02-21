<?php

namespace App\Helper;

class Poisson
{
  /**
   * Factorial function
   *
   * @param  integer $number
   * @return integer
   */
  public function factorial($number)
  {
    $sum = 1;
    for ($i = 1; $i <= floor($number); $i++) {
      $sum *= $i;
    }

    return $sum;
  }

  /**
   * Poisson function.
   *
   * @param  integer $chance
   * @param  integer $occurrence
   * @return float
   */
  public function poisson($chance, $occurrence)
  {
    return exp(-$chance) * pow($chance, $occurrence) / $this->factorial($occurrence);
  }

  /**
   * Poisson function returned as a percentage of 100.
   *
   * @param  integer $chance
   * @param  integer $occurrence
   * @return float
   */
  public function percentage($chance, $occurrence)
  {
    return $this->poisson($chance, $occurrence) * 100;
  }

  /**
   * Poisson function returned as rounded a percentage of 100.
   *
   * @param  integer $chance
   * @param  integer $occurrence
   * @param  integer $dp
   * @return float
   */
  public function roundedPercentage($chance, $occurrence, $dp = 0)
  {
    return round($this->percentage($chance, $occurrence), $dp);
  }
}