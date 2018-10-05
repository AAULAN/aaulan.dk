<?php

namespace Aaulan\Seats;

use SeatGroup;
use Lan;
use Log;
use Auth;
use Cache;

class EloquentSeatRepository implements SeatRepository
{
	
	const TABLE_SMALL_WIDTH = 29;
	const TABLE_SMALL_HEIGHT = 15;
	const TABLE_LARGE_WIDTH = 29;
	const TABLE_LARGE_HEIGHT = 20;
	
	public function getSeats() {
		if (Cache::has('seating')) {
			return Cache::get('seating');
		}
		
		$seatgroups = SeatGroup::orderBy('id')->get();
		
		$seats = array();
		$gseatnum = 1;
		
		$taken = array();
		
		$allLanUsers = Lan::curLan()->users;
		foreach ($allLanUsers as $lu) {
			if ($lu->pivot->seat_group_id && $lu->pivot->seat_num){
				if (!isset($taken[$lu->pivot->seat_group_id])) {
					$taken[$lu->pivot->seat_group_id] = array();
				}
				$taken[$lu->pivot->seat_group_id][$lu->pivot->seat_num] = $lu;
			}
		}
		
		foreach ($seatgroups as $sg) {
			
			$seatnum = 1;
			for ($y = 0; $y < $sg['ycount']; $y++) {
				for ($x = 0; $x < $sg['xcount']; $x++) {
					$seat = new \stdClass;
					$seat->group = $sg->id;
					$seat->seatnum = $seatnum;
					$seat->gseatnum = $gseatnum;
					$classes = array();
					$classes[] = 'table';
					if ($sg['orientation'] == 'h') {
						$classes[] = 'table-horizontal';
					} else {
						$classes[] = 'table-vertical';
					}
					
					$classes[] = 'table-'.$sg['size'];
					$user = (isset($taken[$sg->id][$seatnum]) ? $taken[$sg->id][$seatnum] : null);
					
					if ($user) {
						$seat->user = new \stdClass;
						$seat->user->id = ($user->id);
						$seat->user->name = ($user->display_name != "" ? $user->display_name : $user->name);
						
					} else {
						$seat->user = null;
					}
					$seat->class = implode(' ',$classes);
					$seat->xpos = $sg['xpos'] + $x*($sg['orientation'] == 'h'?($sg['size'] == 'large' ? self::TABLE_LARGE_WIDTH : self::TABLE_SMALL_WIDTH):($sg['size'] == 'large' ? self::TABLE_LARGE_HEIGHT : self::TABLE_SMALL_HEIGHT));
					$seat->ypos = $sg['ypos'] + $y*($sg['orientation'] == 'h'?($sg['size'] == 'large' ? self::TABLE_LARGE_HEIGHT : self::TABLE_SMALL_HEIGHT):($sg['size'] == 'large' ? self::TABLE_LARGE_WIDTH : self::TABLE_SMALL_WIDTH));
					
					
					
					$seats[] = $seat;
					
					$seatnum++;
					$gseatnum++;
				}
				
				
			}
			
		}
		Cache::forever('seating',$seats);
		return $seats;
	}
	
}
