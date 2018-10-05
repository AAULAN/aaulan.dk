<?php

return array(
'title'=>'Pizza Order Pizza',
'single'=>'pizza order pizza',
'model'=>'PizzaorderPizza',
'columns'=>array(
	'pizzaorder_id',
	'pizza_id',
	'quantity',
	'comment',
	'extras',
	'extra_price'
),
'edit_fields'=>array(
	'pizzaorder_id',
	'pizza_id',
	'quantity',
	'comment',
	'extras',
	'extra_price'
),
'filters'=>array(
	'pizzaorder_id',
	'pizza_id',
	'quantity',
	'comment',
	'extras',
	'extra_price'
),

'permission'=> function() {
	return Auth::user()->can('pizzas_super');
},

);
