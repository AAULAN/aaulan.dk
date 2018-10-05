<?php

return array(
'title'=>'Slides',
'single'=>'slide',
'model'=>'Slide',
'form_width'=>800,
'columns'=>array(
	'content'=>array('title'=>'Body','output'=>function($value) { return str_limit($value,50,'...'); }),
	'active'=>array('title'=>'Active','output'=>function($val) { return $val? 'Yes':'No';}),
	'active_from'=>array('title'=>'Show from'),
	'active_to'=>array('title'=>'Show to'),
	'duration'=>array('title'=>'Show in (s)'),
),
'edit_fields'=>array(
	'content'=>array('title'=>'Body','type'=>'textarea'),
	'active'=>array('title'=>'Active','type'=>'bool'),
	'active_from'=>array('title'=>'Show from','type'=>'datetime'),
	'active_to'=>array('title'=>'Show to','type'=>'datetime'),
	'duration'=>array('title'=>'Show in (s)','type'=>'number'),
),
'rules'=>array(
	'content'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('slides');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('slides_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('slides_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('slides_edit');
	},
)

);
