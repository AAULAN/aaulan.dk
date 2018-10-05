<?php

return array(
'title'=>'Pizza Toppings',
'single'=>'pizza topping',
'model'=>'Pizzaextra',
'columns'=>array(
	'name'=> array('title'=>'Name'),
	'price'=>array('title'=>'Price'),
),
'edit_fields'=>array(
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
