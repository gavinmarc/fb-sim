<?php

namespace App\Enums;

abstract class Enum 
{
  public static function keys()
  {
    $class = new \ReflectionClass(get_called_class());
    return array_keys($class->getConstants());
  }

  public static function values()
  {
    $class = new \ReflectionClass(get_called_class());
    return array_values($class->getConstants());
  }
}