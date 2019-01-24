<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stats extends Model
{
  public function user() {
    return $this->belongsTo('App\User');
  }

  public function keys() {
    return $this->hasMany('App\Key');
  }

  public function speeds() {
    return $this->hasMany('App\Speed');
  }
}
