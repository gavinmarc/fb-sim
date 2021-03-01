<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
  use HasFactory;

  protected $casts = [
    'completed' => 'boolean'
  ];

  public function season()
  {
    return $this->belongsTo(Season::class);
  }

  public function entries()
  {
    return $this->hasMany(TableEntry::class)
      ->orderBy('position', 'asc');
  }

  public function isCompleted()
  {
    return $this->completed;
  }
}
