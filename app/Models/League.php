<?php

namespace App\Models;

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

  public function table()
  {
    return $this->teams->sortBy([
      fn ($a, $b) => $b['statistic']['pts'] <=> $a['statistic']['pts'],
      fn ($a, $b) => $b['statistic']['gd'] <=> $a['statistic']['gd'],
      fn ($a, $b) => $b['statistic']['gs'] <=> $a['statistic']['gs'],
    ]);
  }
}
