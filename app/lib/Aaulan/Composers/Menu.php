<?php

namespace Aaulan\Composers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\HTML;

class Menu {
	
	public function compose($view) {
		HTML::macro('menu_element',function($action,$arg1,$arg2 = null) {
			if (is_array($arg1)) {
				$arguments = $arg1;
				$text = $arg2;
			} else {
				$arguments = array();
				$text = $arg1;
			}
			return sprintf('<li class="%s"><a href="%s">%s</a></li>',(Route::currentRouteAction() == $action?'active':''),URL::action($action,$arguments),$text);
		});
		
		$view->with('currentRoute',Route::currentRouteAction());
	}
	
}
