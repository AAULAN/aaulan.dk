<?php

class Schedule extends Eloquent {
	
	protected $table = 'schedule';
	
	public $timestamps = false;
	
	protected $dates = array('starts','ends');
	
}
