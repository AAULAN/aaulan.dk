<?php

class ValidationToken extends Eloquent {
	
	protected $fillable = array('token');
	
	public function user() {
		return $this->belongsTo('User');
	}
}
