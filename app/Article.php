<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{

  protected $fillable = ['title', 'content', 'description', 'author_id'];
  protected $with = ['author'];

  public function author() {
    return $this->belongsTo('App\User');
  }
}
