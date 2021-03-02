<?php

namespace App\Http\Controllers;

use App\Models\Fixture;
use App\Models\Record;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class RecordController extends Controller
{
  /**
   * Handle the incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function __invoke(Request $request)
  {
    return view('record', [
      'recordGroups' => Record::query()
        ->with(['recordable' => function (MorphTo $morphTo) {
          $morphTo->morphWith([Fixture::class => ['homeTeam', 'awayTeam']]);
        }])
        ->get()
        ->groupBy('recordable_type')
    ]);
  }
}
