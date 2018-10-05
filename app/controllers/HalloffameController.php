<?php

class HalloffameController extends BaseController {

	public function index() {
		$winners = Lan::with('winners')->orderBy('id','DESC')->get();

		return Response::view('halloffame.winners', ['lans'=>$winners]);

	}

}
