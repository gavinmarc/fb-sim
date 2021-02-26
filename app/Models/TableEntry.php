<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableEntry extends Model
{
  use HasFactory;

  public function table()
  {
    return $this->belongsTo(Table::class);
  }

  public function team()
  {
    return $this->belongsTo(Team::class);
  }
}
