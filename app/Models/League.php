<?php

namespace App\Models;

use Facades\App\Services\StatisticService;
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
    return $this->hasMany(Fixture::class);
  }

  public function getMatchdaysAttribute()
  {
    return $this->teams->count() * 2;
  }

  public function getFixturesPerMatchdayAttribute()
  {
    return (int) $this->teams->count() / 2;
  }

  public function getLogoAttribute()
  {
    $name = \Str::kebab($this->name);
    return "img/logos/leagues/{$name}.png";
  }

  public function table(int $currentSeason)
  {
    $this->load('teams', 'teams.homeFixtures', 'teams.awayFixtures');

    return $this->teams
      ->map(function ($team) use ($currentSeason) {
        $teamStatistic = StatisticService::team($team, $currentSeason);
        return array_merge($team->attributesToArray(), $teamStatistic);
      })->sortBy([
        fn ($a, $b) => $b['pts'] <=> $a['pts'],
        fn ($a, $b) => $b['gd'] <=> $a['gd']
        // TODO: Error when gd = 0
        // fn ($a, $b) => $b['gd'] <=> $a['gd']
      ]);
  }
}
