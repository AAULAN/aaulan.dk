<?php

return array(
'title'=>'Team-User association',
'single'=>'team-user',
'model'=>'TeamUser',
'columns'=>array(
	'user'=>array('title'=>'User','relationship'=>'user','select'=>"if((:table).display_name != '',(:table).display_name,(:table).name)"),
	'team'=> array('title'=>'Team','relationship'=>'team','select'=>'(:table).name'),
	'accepted'=>array('title'=>'Accepted','output'=>function($val) { return $val ? 'Yes':'No'; }),
),
'edit_fields'=>array(
	'user'=>array('title'=>'User','type'=>'relationship','name_field'=>'display_sanitized'),
	'team'=>array('title'=>'Team','type'=>'relationship','name_field'=>'name'),
	'accepted'=>array('title'=>'Accepted','type'=>'bool',)
),
'filters'=>array(
	'user'=>array(
		'title'=>'User',
		'type'=>'relationship',
		'name_field'=>'display_sanitized'
	),
	'team'=>array(
		'title'=>'Team',
		'type'=>'relationship',
		'name_field'=>'name'
	)
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
