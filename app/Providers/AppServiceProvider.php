<?php

namespace App\Providers;

use App\Models\Fixture;
use App\Models\Record;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Model::unguard();

    Fixture::addGlobalScope('ordered_fixtures', function (Builder $builder) {
      $builder->orderBy('season_id', 'desc')->orderBy('matchday', 'desc');
    });
  }
}