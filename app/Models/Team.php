<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = [
    'name', 'short_name'
  ];

  public function league()
  {
    return $this->belongsTo(League::class);
  }

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

  public function fixtures()
  {
    return $this->homeFixtures->merge($this->awayFixtures)
      ->sortByDesc('matchday');
  }

  public function getLogoAttribute()
  {
    return "/img/logos/teams/{$this->short_name}.png";
  }
}
