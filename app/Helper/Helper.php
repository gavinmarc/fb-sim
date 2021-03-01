<?php

use App\Models\Team;

function matchdays()
{
  return (Team::count() - 1) * 2 ;
}

function fixturesPerMatchday()
{
  return (int) (Team::count() / 2);
}