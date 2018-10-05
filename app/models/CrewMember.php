<?php

class CrewMember extends Eloquent {
	
	protected $table = 'crewmembers';
	
	public function user() {
		return $this->belongsTo('User', 'user_id');
	}

}
