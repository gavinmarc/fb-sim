<?php

namespace App\Models;

use App\Enums\OutcomeEnum;
use App\Helper\Outcome;
use Facades\App\Services\FixtureService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fixture extends Model
{
  use HasFactory, SoftDeletes;

  protected $appends = [
    'outcome'
  ];

  public function scopeCompletedForSeason(Builder $query, int $seasonId)
  {
    return $query->completed()->forSeason($seasonId);
  }

  public function scopeForSeason(Builder $query, int $seasonId)
  {
    return $query->where('season_id', $seasonId);
  }

  public function scopeCompleted(Builder $query)
  {
    return $query->whereNotNull('home_team_goals')->whereNotNull('away_team_goals');
  }

  public function scopeNotCompleted(Builder $query)
  {
    return $query->whereNull('home_team_goals')->whereNull('away_team_goals');
  }

  public function league()
  {
    return $this->belongsTo(League::class);
  }

  public function season()
  {
    return $this->belongsTo(Season::class);
  }

  public function homeTeam()
  {
    return $this->belongsTo(Team::class);
  }

  public function awayTeam()
  {
    return $this->belongsTo(Team::class);
  }

  public function getOutcomeAttribute()
  {
    return $this->isCompleted() ? 
      Outcome::get($this->home_team_goals, $this->away_team_goals) : 
      null;
  }

  public function isCompleted()
  {
    return !is_null($this->home_team_goals) && !is_null($this->away_team_goals);
  }

  public function simulate()
  {
    $result = FixtureService::simulate($this);

    $this->update([
      'home_team_goals' => $result->homeGoals,
      'away_team_goals' => $result->awayGoals,
    ]);
  }

}
