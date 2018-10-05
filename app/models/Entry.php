<?php

class Entry extends Eloquent {
	
	public function user() {
		return $this->belongsTo('User');
	}

	public function lan() {
		return $this->belongsTo('Lan');
	}
	
}
