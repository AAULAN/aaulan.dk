<?php

return array(
'title'=>'Pages',
'single'=>'page',
'model'=>'Page',
'form_width'=>800,
'columns'=>array(
	'title'=>array('title'=>'Title'),
	'body'=>array('title'=>'Body','output'=>function($value) { return str_limit($value,50,'...'); }),
	'slug'=>array('title'=>'SLUG'),
	'category_id'=>array('title'=>'Category ID'),
	'created_at'=> array('title'=>'Created At'),
	'updated_at'=> array('title'=>'Updated At'),
),
'edit_fields'=>array(
	'title'=>array('title'=>'Title','type'=>'text'),
	'body'=>array('title'=>'Body','type'=>'markdown'),
	'category_id'=>array('title'=>'Category ID','type'=>'number'),
	'slug'=>array('title'=>'SLUG','editable'=>false)
),
'rules'=>array(
	'title'=>'required',
	'body'=>'required',
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
