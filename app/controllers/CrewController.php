<?php

class CrewController extends BaseController {
	
	public function index() {

		// return Response::view('crew.index', array('active_members' => CrewMember::where('active', true)->orderBy(DB::raw('RAND()'))->get()));
		return Response::view('crew.index', array('members' => CrewMember::orderBy(DB::raw('RAND()'))->get()));
	}
	
}
