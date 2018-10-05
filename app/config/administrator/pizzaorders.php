<?php

return array(
'title'=>'Pizza Orders',
'single'=>'pizza order',
'model'=>'Pizzaorder',
'columns'=>array(
	'id'=>array('title'=>'Order ID'),
	'round'=> array('title'=>'Round','relationship'=>'round','select'=>'(:table).name'),
	'name'=>array('title'=>'Name','relationship'=>'user','select'=>"(:table).name"),
	'display_name'=>array('title'=>'Display Name','relationship'=>'user','select'=>"(:table).display_name"),
	'order'=>array('title'=>'Order'),
	'price'=>array('title'=>'Price'),
	'state'=>array('title'=>'State'),
	'paid_at'=>array('title'=>'Paid at'),
	'ordered_at'=>array('title'=>'Ordered at'),
	
),
'edit_fields'=>array(
	'id'=>array('title'=>'Order ID','type'=>'key'),
        'state'=>array('title'=>'State'),
	'round'=>array('title'=>'Round','type'=>'relationship','name_field'=>'name','editable'=>false),
	'user'=>array('title'=>'User','type'=>'relationship','name_field'=>'display_sanitized','editable'=>false),
	
	'price'=>array('title'=>'Price','type'=>'number','editable'=>false),
	
	
	
),
'filters'=>array(
	'id'=>array('title'=>'Order ID','type'=>'key'),
	'round'=>array('title'=>'Round','type'=>'relationship','name_field'=>'name'),
	'user'=>array('title'=>'User','type'=>'relationship','name_field'=>'name'),
	'state' => array(
    'type' => 'enum',
    'title' => 'State',
    'options' => array(
        'NEW' => 'NEW',
        'PAID' => 'PAID',
        'ORDERED' => 'ORDERED'
        
    ),
),
	
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
		return false;
	},
),
'actions'=>array(
'pay' => array(
                'title'=>"PAY",
                'messages'=>array(
                        'active'=>'Paying...',
                        'success'=>'Paid',
                        'error'=>'There was an error while setting paid flag'
                ),
                'action'=> function($model)
                {

                        $model->state = 'PAID';
                        $model->paid_at = \Carbon\Carbon::now();

                        $model->save();
                        return true;
                },
        ),
)

);
