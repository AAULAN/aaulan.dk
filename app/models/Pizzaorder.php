<?php

class Pizzaorder extends Eloquent {
	
	protected $softDelete = true;

	protected $dates = array('ordered_at');

	public function round() {
		return $this->belongsTo('PizzaorderRound','round_id');
	}
	public function user() {
		return $this->belongsTo('User');
	}
	public function pizzas() {
		return $this->belongsToMany('Pizza','pizzaorder_pizza')->withPivot('quantity','comment','extras','extra_price');
	}
	
	public function getOrderAttribute() {
		$str = "";
		$q = Pizzaextra::all();
		$extras = array();
		foreach ($q as $e) {
			$extras[$e->id] = sprintf("%s (%s)",$e->name,$e->price);
		}
		$first = true;
		foreach ($this->pizzas as $p) {
			$str .= sprintf("%s%dp #%d %s%s",$first?'':'<br />',$p->pivot->quantity,$p->pizza_id,$p->name,($p->pivot->comment != ""?' : '.$p->pivot->comment:''));
			if ($p->pivot->extras != "") {
				$pieces = explode(',',$p->pivot->extras);
				$e = array();
				foreach ($pieces as $id) if (array_key_exists($id,$extras)) $e[] = $extras[$id];
				$str .= sprintf(" (%s)",join(', ',$e));
			}
			$first = false;
		}
		return $str;
	}
	
	
}
