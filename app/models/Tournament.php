<?php

class Tournament extends Eloquent
{
  protected $dates = array('signup_closes_at','begins_at');

  public function teams() {
    return $this->belongsToMany('Team')->withTimestamps();
  }
  
  public function users() {
    return $this->belongsToMany('User')->withTimestamps();
  }

  public function lan() {
    return $this->belongsTo('Lan');
  }

}