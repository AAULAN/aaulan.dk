<?php

class Lan extends Eloquent {
	
	protected $fillable = ['name','description','opens','closes','price_member','price_nonmember','ticket_link','seats'];
	
	protected $dates = array('opens','closes');
	public function scopeActive($query) {
		return $query->where('active',1);
	}
	
	public static function curLan() {
		return Lan::active()->first();
	}
	
	public function users() {
		return $this->belongsToMany('User')->withPivot('ticket_id','seat_group_id','seat_num')->withTimestamps();
	}

	public function winners() {
		return $this->hasMany('Winner');
	}
	
}
