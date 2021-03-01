<?php

namespace App\Enums;

abstract class RecordTypeEnum extends Enum
{
  const FIXTURE = 'fixture';
  const SEASON = 'season';
  const TEAM = 'team';
}