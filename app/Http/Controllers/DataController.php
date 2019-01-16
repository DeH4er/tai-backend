<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DataController extends Controller
{
  public function open() {
    $data = 'all could see it';
    return response()->json(compact('data'), 200);
  }

  public function closed() {
    $data = 'only authorized users could see it';
    return response()->json(compact('data'), 200);
  }
}
