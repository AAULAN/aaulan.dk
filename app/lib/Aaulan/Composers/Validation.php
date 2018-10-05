<?php

namespace Aaulan\Composers;

use Auth;

class Validation {
	
	public function compose($view) {
		$showValidation = 0;
		if (Auth::check()) {
			$user = Auth::user();
			if ($user->validated == 0) {
				$showValidation = 1;
			}
		}
		
		$view->with('showValidation',$showValidation);
	}
	
}
