<?php

return array(
'title'=>'Pizzas',
'single'=>'pizza',
'model'=>'Pizza',
'columns'=>array(
	'pizza_id'=>array('title'=>'No.'),
	'name'=> array('title'=>'Name'),
	'price'=>array('title'=>'Price'),
),
'edit_fields'=>array(
	'pizza_id'=>array('title'=>'No.','type'=>'text'),
	'name'=>array('title'=>'Name','type'=>'text'),
	'price'=>array('title'=>'price','type'=>'number','decimals'=>2,'thousands_seperator'=>'.','decimal_separator'=>','),
),
'rules'=>array(
	'name'=>'required',
),
'permission'=> function() {
	return Auth::user()->can('pizzas');
},
'action_permissions'=>array(
	'delete' => function($model) {
		return Auth::user()->can('pizzas_edit');
	},
	'update' => function($model) {
		return Auth::user()->can('pizzas_edit');
	},
	'create' => function($model) {
		return Auth::user()->can('pizzas_edit');
	},
)

);
