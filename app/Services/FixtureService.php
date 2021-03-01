<?php

namespace App\Services;

use App\Events\MatchdayCompleted;
use App\Models\Helper\Result;
use App\Models\Fixture;
use Facades\App\Services\MatchProbabilityService;
use Illuminate\Support\Collection;

class FixtureService
{
  /**
   * Creates probibilities for the given fixtures for following events:
   * match outcome, over/under, possible results
   * 
   * @param  mixed $fixtures
   * @param  boolean $force
   * @return void
   */
  public function createProbibilities($fixtures, bool $force = false)
  {
    if (!$fixtures instanceof Collection) {
      $fixtures = collect($fixtures);
    }

    foreach ($fixtures as $fixture) {
      // check if relation already exists
      if ($fixture->probabilities && !$force) {
        continue;
      }

      // calculate probabilities
      $results = MatchProbabilityService::calculate($fixture);
      $outcome = MatchProbabilityService::cummulativeResultProbibilities($results);
      $over_under = MatchProbabilityService::overUnderProbibilities($results);

      // update or create relation
      $fixture->probabilities()
        ->updateOrCreate([], compact('outcome', 'over_under', 'results'));
    }
  }

  /**
   * Simulates all given fixtures.
   * 
   * @param  Collection $fixtures
   * @return void
   */
  public function simulateMatchday(Collection $fixtures)
  {
    $fixtures->each->simulate();

    event(new MatchdayCompleted($fixtures));
  }

  /**
   * Simulates a fixture for the given teams.
   * 
   * @param  Fixture $fixture
   * @return Result         
   */
  public function simulate(Fixture $fixture)
  {
    if (!$fixture->probabilities) {
      throw new \Exception('FixtureService: Missing relation for fixture');
    }

    $resultsProb = $fixture->probabilities->results;
    $outcomeProb = $fixture->probabilities->outcome;

    $outcome = $this->randomFromSet($outcomeProb->toCollection());

    return $this->randomResult($outcome, $resultsProb);
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
  private function randomFromSet(Collection $set, int $length = 100000)
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