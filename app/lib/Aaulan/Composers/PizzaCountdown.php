<?php

namespace Aaulan\Composers;
use PizzaorderRound;
use Carbon\Carbon;

class PizzaCountdown {
	
	public function compose($view) {
		
		$round = PizzaorderRound::getOpen();
		$showcountdown = 0;
		if ($round != null) {
			$showcountdown = 1;
			$time = $round->closes->diffInSeconds(Carbon::now())*1000;
		} else {
			$time = 0;
		}
		
		
		$view->with('showPizzaCountdown',$showcountdown);
		$view->with('pizzaCountdownTime',$time);
	}
	
}
