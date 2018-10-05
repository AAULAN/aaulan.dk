<?php

use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission {
	
	protected $fillable = array('name','display_name');
	
	public function roles() {
		return $this->belongsToMany('Role');
	}
	
	
}
