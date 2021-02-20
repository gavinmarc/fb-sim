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

  public function matches()
  {
    return $this->hasMany(Match::class);
  }

  public function getMatchdaysAttribute()
  {
    return $this->teams->count() * 2;
  }

  public function getLogoAttribute()
  {
    $name = \Str::kebab($this->name);
    return "img/logos/leagues/{$name}.png";
  }
}
