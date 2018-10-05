<?php

namespace Aaulan\Seats;

use Illuminate\Support\ServiceProvider;

class SeatsServiceProvider extends ServiceProvider {
	
	public function register() {
		$this->app->bind('Aaulan\Seats\SeatRepository',
						 'Aaulan\Seats\EloquentSeatRepository');
	}
}
