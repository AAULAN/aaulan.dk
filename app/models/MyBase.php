<?php

class MyBase extends Eloquent {
	
	public function newPivot(Eloquent $parent, array $attributes, $table, $exists) {
		return new PizzaorderPizza($parent,$attributes,$table,$exists);
		if ($parent instanceof Pizza || $parent instanceof Pizzaorder) {
			$pivot = PizzaorderPizza::where('pizza_id',$attributes['pizza_id'])->where('pizzaorder_id',$attributes['pizzaorder_id'])->first();
			if ($pivot) return $pivot;
		}

	}
}
