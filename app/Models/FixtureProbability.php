<?php

namespace App\Models;

use App\Casts\OutcomeCast;
use App\Casts\OverUnderCast;
use App\Casts\ResultCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixtureProbability extends Model
{
  use HasFactory;

  protected $casts = [
    'outcome' => OutcomeCast::class,
    'over_under' => OverUnderCast::class,
    'results' => ResultCast::class,
  ];

  public function fixture()
  {
    return $this->belongsTo(Fixture::class);
  }
}
