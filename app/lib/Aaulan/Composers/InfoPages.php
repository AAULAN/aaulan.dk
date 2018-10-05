<?php

namespace Aaulan\Composers;
use Page;

class InfoPages {
	
	public function compose($view) {
		
		$pages = Page::all();
		
		$view->with('pageList',$pages);
	}
	
}
