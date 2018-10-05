<?php

class SlideshowController extends BaseController {
	
	public function getIndex() {
		
		return Response::view('slideshow.index');
	}
	
	public function getNextUp() {
		$nextup = Schedule::whereRaw('starts > now()')->orderBy('starts')->first();
		$nu = array(
			'name' => $nextup->name,
			'at'=>$nextup->starts->format('l H:i')
		);
		return Response::json(array('nextup'=>$nu));
	}
	
	public function getNextSlide($curSlide) {
		if ($curSlide == 0) {
			$slide = DB::select('select * from slides where (active_to > now() or active_to is null) and (active_from < now() or active_from is null) and active = 1 order by id limit 1');
		} else {
			$slide = DB::select('select * from slides where (active_to > now() or active_to is null) and (active_from < now() or active_from is null) and active = 1 and id > ? order by id limit 1',array($curSlide));
			if ($slide == null) {
				$slide = DB::select('select * from slides where (active_to > now() or active_to is null) and (active_from < now() or active_from is null) and active = 1 order by id limit 1');
			}
		}
		$theslide = $slide[0];		
		if ($theslide == null) {
			App::abort(404,'No slide found');
		}
		return Response::json(array('content'=>$theslide->content,'id'=>$theslide->id,'duration'=>$theslide->duration));
		
	}
	
}
