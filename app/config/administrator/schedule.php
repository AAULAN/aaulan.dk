<?php

return array(
'title'=>'Schedule',
'single'=>'schedule',
'model'=>'Schedule',
'form_width'=>800,
'columns'=>array(
	'starts'=>array('title'=>'From'),
	'ends'=>array('title'=>'To'),
	'name'=>array('title'=>'Item'),
	'description'=>array('title'=>'Extended description','output'=>function($value) { return str_limit($value,50,'...'); }),
),
'edit_fields'=>array(
	'starts'=>array('title'=>'From','type'=>'datetime'),
	'ends'=>array('title'=>'To','type'=>'datetime'),
	'name'=>array('title'=>'Item','type'=>'text'),
	'description'=>array('title'=>'Extended description','type'=>'textarea'),
	
),
'rules'=>array(
	'name'=>'required',
	'starts'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('schedule');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('schedule_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('schedule_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('schedule_edit');
	},
)

);
