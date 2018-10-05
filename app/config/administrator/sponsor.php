<?php

return array(
'title'=>'Sponsors',
'single'=>'sponsor',
'model'=>'Sponsor',
'columns'=>array(
	'name'=>array('title'=>'Name'),
        'gift'=>array('title'=>'What they sponsor'),
        'link'=>array('title'=>'URL'),
        'logo'=>array('title'=>'Logo'),
        'created_at'=> array('title'=>'Created At'),
        'updated_at'=> array('title'=>'Updated At'),
),
'edit_fields'=>array(
	'name'=>array('title'=>'Name','type'=>'text'),
        'gift'=>array('title'=>'What they sponsor','type'=>'markdown'),
        'link'=>array('title'=>'URL'),
        'logo'=>array('title'=>'Logo','type'=>'text')
),
'rules'=>array(
        'name'=>'required',
        'gift'=>'required',
        'link'=>'required',
        'logo'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('pages');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('pages_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('pages_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('pages_edit');
	},
)

);
