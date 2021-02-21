<?php

namespace App\Helper;

use App\Enums\OutcomeEnum;

class Outcome
{
  /**
   * Returns the match outcome for the given team goals.
   * 
   * @param  integer $hGoals
   * @param  integer $aGoals
   * @return string
   */
  public static function get(int $hGoals, int $aGoals)
  {
    if ($hGoals == $aGoals) {
      return OutcomeEnum::DRAW;
    }
    
    return $hGoals > $aGoals ? OutcomeEnum::HOME : OutcomeEnum::AWAY;
  }

}