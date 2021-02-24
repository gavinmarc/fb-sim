<?php

namespace App\Services;

use App\Models\Result;
use App\Models\Fixture;
use Facades\App\Services\MatchProbabilityService;
use Illuminate\Support\Collection;

class FixtureService
{
  /**
   * Simulates a fixture for the given teams.
   * 
   * @param  Fixture $fixture
   * @return Result         
   */
  public function simulate(Fixture $fixture)
  {
    $results = MatchProbabilityService::calculate($fixture);

    $predictions = MatchProbabilityService::cummulativeResultProbibilities($results);

    $outcome = $this->randomFromSet($predictions);

    return $this->randomResult($outcome, $results);
  }

  /**
   * Picks a random result from the given result set.
   * 
   * @param  string $outcome  
   * @param  Collection $results 
   * @return Result            
   */
  private function randomResult(string $outcome, Collection $results)
  {
    $filteredResults = $results->where('outcome', $outcome)
      ->reject(fn ($result) => $result->probability == 0)
      ->values();

    $set = $filteredResults->pluck('probability');

    $index = $this->randomFromSet($set);

    return $filteredResults->get($index);
  }

  /**
   * Returns a random key for the given set.
   * 
   * @param  Collection $set
   * @param  integer $length
   * @return mixed
   */
  private function randomFromSet(Collection $set, int $length = 10000)
  {
    $set = $set->mapWithKeys(fn ($prob, $key) => [$key => $prob * $length]);

    $max = (int) collect($set)->sum();

    $random = mt_rand(0, $max);
    
    $totalProb = 0;

    $callback = function ($result) use (&$totalProb, $random) {
      $totalProb += $result;
      return $random <= $totalProb;
    };

    return $set->filter($callback)->keys()->first();
  }

}