<?php

use Aaulan\Seats\SeatRepository as Seat;

class SeatController extends BaseController {
	
	public function __construct(Seat $seat) {
		$this->seat = $seat;
	}
	
	public function getSeating() {
		return Response::view('seating.chart');
		
	}
	
	public function getSeatsJson() {
		$seats = $this->seat->getSeats();
		return Response::json($seats);
	}
	public function postUnseatJson() {
		$curLanId = Lan::curLan()->id;
		$lanuser = LanUser::whereUserId(Auth::user()->id)->whereLanId($curLanId)->first();
		$lanuser->seat_group_id = null;
		$lanuser->seat_num = null;
		$lanuser->gseatnum = 0;
		$lanuser->save();
		Cache::forget('seating');
	}
	public function postSeatsJson() {
	
		if (Input::has('group','seatnum')) {
			// is taken?
			$curLanId = Lan::curLan()->id;
			$taken = LanUser::whereLanId($curLanId)->whereSeatGroupId(Input::get('group'))->whereSeatNum(Input::get('seatnum'))->first();
			if ($taken) {
				App::abort(406,'Seat is taken.');
			}
			$seating = $this->seat->getSeats();
			$gseatnum = 0;
			
			foreach ($seating as $seat) {
				if ($seat->group == Input::get('group') && $seat->seatnum == Input::get('seatnum')) {
					$gseatnum = $seat->gseatnum;
					break;
				}
			}
			
			$lanuser = LanUser::whereUserId(Auth::user()->id)->whereLanId($curLanId)->first();
			$lanuser->seat_group_id = Input::get('group');
			$lanuser->seat_num = Input::get('seatnum');
			$lanuser->gseatnum = $gseatnum;
			$lanuser->save();
			Cache::forget('seating');
		}
		
		//$seats = $this->seat->getSeats();
		//return Response::json($seats);
	}
	
	public function getGroupsJson() {
		
		$groups = SeatGroup::all();
		
		return Response::json($groups);
		
	}
	
	public function postGroupsJson() {
		
		if (Input::has('groups')) {
			$groups = Input::get('groups');
			$ids = array();
			foreach ($groups as $group) {
				if (isset($group['id'])) {
					$g = SeatGroup::find($group['id']);					
				} else {
					$g = null;
				}
				
				if ($g) {
					$g->update($group);
				} else {
					$g = SeatGroup::create($group);
				}
				$ids[] = $g->id;
			}
			SeatGroup::whereNotIn('id',$ids)->delete();
			Cache::forget('seating');
		}
		
		return Response::view('seating.seating');
	}
	
	public function adminGetSeating() {
		
		return Response::view('seating.seating');
	}

	public function adminPostSeating() {
		
		return Response::view('seating.seating');
	}
}
