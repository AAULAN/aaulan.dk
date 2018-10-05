<?php

class Team extends Eloquent {
	
	protected $fillable = ['name'];
	
	public function creator() {
		return $this->belongsTo('User','user_id');
	}
	public function users() {
		return $this->belongsToMany('User')->where('accepted','=', '1');
	}
	public function tournaments() {
    return $this->belongsToMany('Tournament')->withTimestamps();
  }
	
}
