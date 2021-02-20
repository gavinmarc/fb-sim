<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fixture extends Model
{
  use HasFactory, SoftDeletes;

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
}
