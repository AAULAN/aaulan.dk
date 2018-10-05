<?php

class PageController extends BaseController {
	
	public function getIndex() {
		
		$pages = Page::all();
		
		return Response::view('page.index',array('pages'=>$pages));
	}
	
	public function getPage(Page $page) {
		return Response::view('page.show',array('page'=>$page));
	}
	
}
