<?php
use Carbon\Carbon;
class PizzaorderRound extends Eloquent {
	
	protected $dates = array('opens','closes','delivery');
	
	public function orders() {
		return $this->hasMany('Pizzaorder','round_id');
	}
	
	public function lan() {
		return $this->belongsTo('Lan');
	}
	
	public static function getOpen() {
		return self::whereRaw('opens < now()')->whereRaw('closes > now()')->first();
	}
	public static function getFirstClosing() {
		return self::whereRaw('opens < now()')->whereRaw('closes > now()')->orderBy('closes')->first();
	}
	
	public static function getAllOpen() {
		return self::whereRaw('opens < now()')->whereRaw('closes > now()')->get();
	}



	public function timeTillCloses() {
		$t = new Carbon($this->closes);
		return $t->diffForHumans();
	}
	
}
