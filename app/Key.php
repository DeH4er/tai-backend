<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Key extends Model
{
  public function stats() {
    return $this->belongsTo('App\Stats');
  }
}