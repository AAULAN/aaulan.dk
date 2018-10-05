<?php

class Pizza extends Eloquent {
	
	public function orders() {
		return $this->belongsToMany('Pizzaorder','pizzaorder_pizza')->withPivot('quantity','comment','extras','extra_price');
	} 
	
	public function extras() {
		return $this->hasManyThrough('Pizzaextra','Pizzaorder');
	}
	
}
