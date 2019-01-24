<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\UserController;

class TrainerController extends Controller
{
  public function sendDict(Request $request) {
    
  }

  public function updateStats(Request $request) {
    $user = UserController::getAuthenticatedUser();
    $user->stats->errors += $request->errors;

    array_push($user->stats->speedArray, $request->speed);

    foreach($request->keySpeed as $req_key => $req_speed_array) {
      if (array_key_exists($req_key, $user->stats->keys)) {
        $user->stats->keys[$req_key]->speed = ($user->stats->keys[$req_key] + $this->aver($req_speed_array)) / 2;
      } else {
        $new_key = new Key;
        $new_key->speed = $this->aver($req_speed_array);
        $new_key->save();
        $user->stats()->keys[$req_key] = $new_key;
      } 

      $user->stats()->keys[$req_key]->save();
    }

    foreach($request->wrongKeyPressCount as $req_key => $wrong_key_press_count) {
      if (array_key_exists($req_key, $user->stats->keys)) {
        $user->stats()->keys[$req_key]->errors += $wrong_key_press_count;
        $user->stats()->keys[$req_key]->save();
      }
    }

    $user->stats()->save();
    return $response()->json([], 200);
  }

  private function aver($arr) {
    if ( ($length = count($arr)) == 0) {
      return 0;
    }

    $sum = 0;
    foreach($arr as $i => $val) {
      $sum += $val;
    }

    return $sum / $length;
  } 
}
