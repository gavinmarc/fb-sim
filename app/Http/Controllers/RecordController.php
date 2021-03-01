<?php

namespace App\Http\Controllers;

use App\Models\Record;
use Facades\App\Services\RecordService;
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
      'records' => Record::all()
    ]);
  }
}
