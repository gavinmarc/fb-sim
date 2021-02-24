<?php

namespace App\Services;

use App\Enums\OutcomeEnum;
use App\Models\Team;

class StatisticService
{
  /**
   * Returns array containing season statistics for the given team.
   *  
   * @param  Team $team
   * @param  int $season
   * @return array
   */
  public function team(Team $team, int $season)
  {
    $callback = fn ($fix) => $fix->season_id == $season && $fix->isCompleted();
    $hf = $team->homeFixtures->filter($callback);
    $af = $team->awayFixtures->filter($callback);

    // matches played
    $mp = $hf->merge($af)->count();

    // wins
    $w = $hf->filter(fn ($f) => $f->outcome == OutcomeEnum::HOME)
      ->merge($af->filter(fn ($f) => $f->outcome == OutcomeEnum::AWAY))
      ->count();

    // draws
    $d = $hf->filter(fn ($f) => $f->outcome == OutcomeEnum::DRAW)
      ->merge($af->filter(fn ($f) => $f->outcome == OutcomeEnum::DRAW))
      ->count();

    // losses
    $l = $hf->filter(fn ($f) => $f->outcome == OutcomeEnum::AWAY)
      ->merge($af->filter(fn ($f) => $f->outcome == OutcomeEnum::HOME))
      ->count();

    // goal difference
    $gf = $hf->sum('home_team_goals') + $af->sum('away_team_goals');
    $ga = $hf->sum('away_team_goals') + $af->sum('home_team_goals');
    $gd = $gf - $ga;

    // points
    $pts = ($w * 3) + $d;

    return compact('mp', 'w', 'd', 'l', 'gf', 'ga', 'gd', 'pts');
  }
}