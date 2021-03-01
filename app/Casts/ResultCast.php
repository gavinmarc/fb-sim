<?php

namespace App\Casts;

use App\Models\Helper\Result;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class ResultCast implements CastsAttributes
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
    return collect(json_decode($value, true))
      ->map(fn ($data) => Result::make($data));
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
    return $value->toJson();
  }
}
