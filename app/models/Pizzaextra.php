<?php

class Pizzaextra extends Eloquent {

	public function pizzas() {
		return $this->belongsToMany('PizzaorderPizza','pizzaorder_pizza_extra');
	}
}
