<?php

require '../bootstrap/autoload.php';
$app = require_once __DIR__.'/../bootstrap/start.php';

use Aaulan\Seats\EloquentSeatRepository as Seat;
$repo = new Seat;

$seats = $repo->getSeats();

foreach ($seats as $seat) {
	if (!$seat->user) continue;
	$lu = LanUser::where('seat_group_id','=',$seat->group)->where('seat_num','=',$seat->seatnum)->first();
	if (!$lu) {
		printf("lu not found for %s\n",$seat->user->name);
		continue;
	}
	$lu->gseatnum = $seat->gseatnum;
	$lu->save();
}
