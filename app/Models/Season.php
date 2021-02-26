<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
  use HasFactory;

  public static function lastSeason($model)
  {
    return $model->fixtures()
      ->orderBy('season_id', 'desc')
      ->select(['season_id'])
      ->distinct('season_id')
      ->pluck('season_id')
      ->get(1);
  }

  public static function currentSeason($model)
  {
    return $model->fixtures()
      ->completed()
      ->max('season_id');
  }
}
