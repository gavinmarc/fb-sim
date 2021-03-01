<?php

namespace App\Models;

use Facades\App\Services\StatisticService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
  use HasFactory, SoftDeletes;

  protected $appends = [
    'logo'
  ];

  public function homeFixtures()
  {
    return $this->hasMany(Fixture::class, 'home_team_id')
      ->orderBy('matchday', 'desc');
  }

  public function awayFixtures()
  {
    return $this->hasMany(Fixture::class, 'away_team_id')
      ->orderBy('matchday', 'desc');
  }

  public function getFixturesAttribute()
  {
    return $this->homeFixtures->merge($this->awayFixtures)
      ->sortByDesc('matchday');
  }

  public function getLogoAttribute()
  {
    return "/img/logos/teams/{$this->short_name}.png";
  }

  public function getStatisticAttribute()
  {
    $currentSeason = Season::currentSeason($this);

    return StatisticService::team($this, $currentSeason);
  }
}
