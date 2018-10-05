<?php

return array(
'title'=>'Winners',
'single'=>'winner',
'model'=>'Winner',
'columns'=>array(
	'game'=>array('title'=>'Game'),
	'team_name'=>array('title'=>'Team Name'),
	'place'=>array('title'=>'Place'),
	'created_at'=> array('title'=>'Created At'),
	'updated_at'=> array('title'=>'Updated At'),
),
'edit_fields'=>array(
	'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
	'game'=>array('title'=>'Game','type'=>'text'),
	'team_name'=>array('title'=>'Team Name','type'=>'text'),
	'place'=>array('title'=>'Place','type'=>'number'),
	'filename'=>array('title'=>'Picture','type'=>'image','location'=>public_path().'/uploads/originals/','naming'=>'random','length'=>20,'sizes'=>array(array(1140,760,'crop',public_path().'/uploads/1140x760_crop/',100)))

),
'permission'=> function() {
	return Auth::user()->can('winners');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('winners_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('winners_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('winners_edit');
	},
)

);
