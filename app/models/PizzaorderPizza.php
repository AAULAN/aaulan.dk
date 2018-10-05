<?php

use Illuminate\Database\Eloquent\Relations\Pivot;

class PizzaorderPizza extends Eloquent {
	
	protected $table = 'pizzaorder_pizza';
	
	public function pizza() {
		return $this->belongsTo('Pizza');
	}
	public function pizzaorder() {
		return $this->belongsTo('Pizzaorder');
	}
	
}
