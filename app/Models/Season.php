<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
  use HasFactory;

  public static function currentSeason(Team $team)
  {
    return optional($team->fixtures()->last())->season_id;
  }
}
