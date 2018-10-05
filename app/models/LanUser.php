<?php

class LanUser extends Eloquent {
	
	protected $table = 'lan_user';
	
	public function user() {
		return $this->belongsTo('User');
	}
	public function lan() {
		return $this->belongsTo('Lan');
	}
	
	
}
