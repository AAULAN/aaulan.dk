<?php

class Page extends Eloquent {
	
	public static $sluggable = array(
		'build_from' => 'title',
		'save_to' => 'slug',
		'on_update'=>true
	);
	
	
	
}
