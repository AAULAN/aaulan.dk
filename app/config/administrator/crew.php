<?php

return array(
'title'=>'Crew',
'single'=>'crew',
'model'=>'CrewMember',
'form_width'=>800,
'columns'=>array(
	'user'=>array('title'=>'User','relationship'=>'user','select'=>"if((:table).display_name != '',(:table).display_name,(:table).name)"),
	'role'=>array('title'=>'Role'),
	'description'=>array('title'=>'Description','output'=>function($value) { return str_limit($value,50,'...'); }),
),
'edit_fields'=>array(
	//'user_id'=>array('title'=>'User ID','type'=>'number'),
	'user'=>array('title'=>'User','type'=>'relationship','name_field'=>'display_sanitized'),
	'role'=>array('title'=>'Role','type'=>'text'),
	'description'=>array('title'=>'Description','type'=>'textarea'),
	'active'=>array('title'=>'Active','type'=>'bool'),
	'filename'=>array('title'=>'Picture','type'=>'image','location'=>public_path().'/uploads/originals/','naming'=>'random','length'=>20,'sizes'=>array(array(500,300,'crop',public_path().'/uploads/crew/',100)))
),
'rules'=>array(
	'user_id'=>'required',
	'role'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('crew_add');
},

);
