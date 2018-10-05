<?php

class TicketValidationToken extends Eloquent {
	
	protected $fillable = array('token','email');
	
	public function user() {
		return $this->belongsTo('User');
	}
}
