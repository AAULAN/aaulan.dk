<?php

use Aaulan\Seats\SeatRepository as Seat;

class DevelopmentController extends BaseController {
	
	public function __construct(Seat $seat) {
		$this->seat = $seat;
	}
	
	public function getTest() {
		
	}
	
}
