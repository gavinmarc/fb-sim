<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
  use HasFactory;

  protected $fillable = ['years'];

  public static function currentSeason($model)
  {
    return optional($model->fixtures->last())->season_id;
  }
}
