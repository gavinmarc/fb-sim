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

  public function getLogoAttribute()
  {
    return "/img/logos/teams/{$this->short_name}.png";
  }
}
