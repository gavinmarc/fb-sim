<?php

namespace App\Models;

use App\Enums\OutcomeEnum;
use App\Helper\Outcome;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fixture extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'league_id', 'season_id', 'home_team_id', 'away_team_id',
    'matchday', 'home_team_goals', 'away_team_goals'
  ];

  protected $appends = [
    'outcome'
  ];

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
    return Outcome::get($this->home_team_goals, $this->away_team_goals);
  }

}
