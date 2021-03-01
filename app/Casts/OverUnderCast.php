<?php

namespace App\Casts;

use App\Models\Helper\OverUnder;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class OverUnderCast implements CastsAttributes
{
  /**
   * Cast the given value.
   *
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @param  string  $key
   * @param  mixed  $value
   * @param  array  $attributes
   * @return mixed
   */
  public function get($model, $key, $value, $attributes)
  {
    return new OverUnder(json_decode($value, true));
  }

  /**
   * Prepare the given value for storage.
   *
   * @param  \Illuminate\Database\Eloquent\Model  $model
   * @param  string  $key
   * @param  mixed  $value
   * @param  array  $attributes
   * @return mixed
   */
  public function set($model, $key, $value, $attributes)
  {
    return json_encode($value);
  }
}
