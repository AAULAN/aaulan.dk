<?php

return array(
'title'=>'Teams',
'single'=>'team',
'model'=>'Team',
'columns'=>array(
	'id'=>array('title'=>'ID'),
	'name'=> array('title'=>'Name'),
	'user_name'=>array('title'=>'Creator','relationship'=>'creator','select'=>"if((:table).display_name != '',(:table).display_name,(:table).name)"),
	'users'=>array('title'=>'Members','relationship'=>'users','select'=>"group_concat((:table).display_name)"),
	'created_at'=>array('title'=>'Created at'),
	'updated_at'=>array('title'=>'Updated at'),
),
'edit_fields'=>array(
	'name'=>array('title'=>'Name','type'=>'text'),
	'creator'=>array('title'=>'Creator','type'=>'relationship','name_field'=>'display_sanitized'),
),
'rules'=>array(
	'name'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('teams');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('teams_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('teams_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('teams_edit');
	},
)

);
