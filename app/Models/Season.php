<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
  use HasFactory;

  public static function lastSeason()
  {
    return Fixture::query()
      ->completed()
      ->orderBy('season_id', 'desc')
      ->distinct('season_id')
      ->pluck('season_id')
      ->get(1);
  }

  public static function currentSeason()
  {
    return Fixture::completed()->max('season_id');
  }
}
