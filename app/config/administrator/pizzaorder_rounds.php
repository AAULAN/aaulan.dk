<?php
use Carbon\Carbon;
return array(
'title'=>'Pizza Rounds',
'single'=>'pizza rounds',
'model'=>'PizzaorderRound',
'columns'=>array(
	'opens'=>array('title'=>'Opens'),
	'closes'=>array('title'=>'Closes'),
	'delivery'=>array('title'=>'Delivery'),
	'name'=> array('title'=>'Name'),
),
'edit_fields'=>array(
	'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
	'name'=>array('title'=>'Name','type'=>'text'),
	'opens'=>array('title'=>'Opens','type'=>'datetime'),
	'closes'=>array('title'=>'Closes','type'=>'datetime'),
	'delivery'=>array('title'=>'Delivery','type'=>'datetime'),
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
),
'link'=>function($model) {
	return URL::action('PizzaController@getExport',$model->id);
},
'actions'=>array(
	/*'set_ordered' => array(
		'title'=>"MARK AS ORDERED",
		'messages'=>array(
			'active'=>'Marking all as ordered...',
			'success'=>'Marked!',
			'error'=>'There was an error while marking as ordered.'
		),
		'action'=> function($model) {
			foreach ($model->orders as $order) {
				if (!$order->ordered_at) {
					$order->ordered_at = Carbon::now();
					$order->save();	
				}
			}
			return true;
		},
	),*/
	

)

);
