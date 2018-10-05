<?php

class TeamUser extends Eloquent {
	protected $table = 'team_user';
	public $timestamps =false;
	public function user() {
		return $this->belongsTo('User');
	}
	public function team() {
		return $this->belongsTo('Team');
	}
	
}
