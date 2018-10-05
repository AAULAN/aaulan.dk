<?php

return array(
'title'=>'Entries',
'single'=>'entry',
'model'=>'Entry',
'form_width'=>800,
'columns'=>array(
	'user'=>array('title'=>'Poster','relationship'=>'user','select'=>'(:table).name'),
	'title'=>array('title'=>'Title'),
	'body'=>array('title'=>'Body','output'=>function($value) { return str_limit($value,50,'...'); }),
	'created_at'=> array('title'=>'Created At'),
	'updated_at'=> array('title'=>'Updated At'),
),
'edit_fields'=>array(
	'user'=>array('title'=>'Poster','type'=>'relationship','field_name'=>'name'),
	'title'=>array('title'=>'Title','type'=>'text'),
	'body'=>array('title'=>'Body','type'=>'textarea'),
    'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
),
'rules'=>array(
	'title'=>'required',
	'body'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('entries');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('entries_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('entries_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('entries_edit');
	},
)

);
