<?php

class ScheduleController extends BaseController {
	
	public function getIndex()
	{
		$items = Schedule::orderBy('starts')->get();

		return Response::view('schedule.index', array('items' => $items));
	}
	
	public function getGantt()
	{
		$items = Schedule::orderBy('starts')->get();
		
		return Response::view('schedule.gantt', array('items' => $items, 'lan' => Lan::curLan()));
	}
	
}
