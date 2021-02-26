<?php

namespace App\Models;

use Facades\App\Services\SeasonService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class League extends Model
{
  use HasFactory, SoftDeletes;

  protected $appends = [
    'matchdays'
  ];

  public function teams()
  {
    return $this->hasMany(Team::class);
  }

  public function fixtures()
  {
    return $this->hasMany(Fixture::class)
      ->orderBy('season_id', 'desc')
      ->orderBy('matchday', 'desc');
  }

  public function tables()
  {
    return $this->hasMany(Table::class);
  }

  public function getMatchdaysAttribute()
  {
    return ($this->teams()->count() - 1) * 2;
  }

  public function getFixturesPerMatchdayAttribute()
  {
    return (int) ($this->teams->count() / 2);
  }

  public function isSeasonComplete(int $seasonId)
  {
    $playedMatches = $this->fixtures()->where('season_id', $seasonId)->count();
    $totalMatches = $this->matchdays * $this->fixtures_per_matchday;
    return $playedMatches == $totalMatches;
  }

  public function getLogoAttribute()
  {
    $name = \Str::kebab($this->name);
    return "img/logos/leagues/{$name}.png";
  }

  public function nextMatchdayFixtures()
  {
    $lastFixture = $this->fixtures()->completed()->first();
    
    $matchday = $lastFixture->matchday + 1;
    $seasonId = $lastFixture->season_id;

    // check if last season is complete and create new fixtures
    if ($lastFixture->matchday == $this->matchdays) {
      $season = SeasonService::new($this);
      $seasonId = $season->id;
      $matchday = 1;
    }

    return $this->fixtures()
      ->where('season_id', $seasonId)
      ->where('matchday', $matchday)
      ->get();
  }
}
