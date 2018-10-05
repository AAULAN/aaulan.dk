<?php

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole {
	
	
	protected $fillable = array('name');
	
	public function permissions() {
		return $this->belongsToMany('Permission');
	}
	
	public function users() {
		return $this->belongsToMany('User','assigned_roles');
	}
	
}
