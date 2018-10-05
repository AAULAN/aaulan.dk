<?php

return array(
'title'=>'Users',
'single'=>'user',
'model'=>'User',
'columns'=>array(
	'id'=>array('title'=>'ID'),
	'name'=> array('title'=>'Name'),
	'display_name'=>array('title'=>'Display Name'),
	'email'=>array('title'=>'E-mail'),
	'phone'=>array('title'=>'Phone','visible'=>function() {return Auth::user()->can('users_view_confidential');}),
	'ida'=>array('title'=>'IDA member no','visible'=>function() {return Auth::user()->can('users_view_confidential');}),
	'slug'=>array('title'=>'SLUG'),
	'validated'=>array('title'=>'Validated','output'=>function($val) { return $val?'Yes':'No';}),
	'has_ticket'=>array('title'=>'Has ticket','output'=>function($val) { return $val?'Yes':'No';}),
	'created_at'=>array('title'=>'Created at'),
	'updated_at'=>array('title'=>'Updated at'),
	'last_activity'=>array('title'=>'Last activity'),
),
'edit_fields'=>array(
	'name'=>array('title'=>'Name','type'=>'text'),
	'display_name'=>array('title'=>'Display Name','type'=>'text'),
	'email'=>array('title'=>'E-mail','type'=>'text'),
	'phone'=>array('title'=>'Phone','type'=>'text','visible'=>function() {return Auth::user()->can('users_view_confidential');}),
	'ida'=>array('title'=>'IDA member no','type'=>'text','visible'=>function() {return Auth::user()->can('users_view_confidential');}),
	'slug'=>array('title'=>'SLUG','type'=>'text'),
	'validated'=>array('title'=>'Validated','type'=>'bool'),
),
'filters'=>array(
	'name'=>array(
		'title'=>'Name',
		'type'=>'text'
	),
	'display_name'=>array(
		'title'=>'Display Name',
		'type'=>'text'
	),
	'email'=>array(
		'title'=>'E-mail',
		'type'=>'text'
	),
),
'rules'=>array(
	'name'=>'required',
	'email'=>'required|email|unique:users',
	'password'=>'required|min:8',
	'display_name'=>'max:25',
	'phone'=>'numeric',
	'ida'=>'numeric',
),
'permission'=> function() {
	return Auth::user()->can('users');
},
'action_permissions'=>array(
	'delete' => function($model) {
		if ($model->id == 1) return false;
		return Auth::user()->can('users_edit');
	},
	'update' => function($model) {
		if ($model->id == 1) return false;
		return Auth::user()->can('users_edit');
	},
	'create' => function($model) {
		if ($model->id == 1) return false;
		return Auth::user()->can('users_edit');
	},
),
'actions' => array(
	'regen_slug' => array(
		'title'=>"Regenerate SLUG",
		'messages'=>array(
			'active'=>'Regenerating...',
			'success'=>'Regenerated',
			'error'=>'There was an error while regenerating'
		),
		'action'=> function($model) {
			Sluggable::make($model,true);
			
			$model->save();
			return true;
		},
	),
	'admin_give_ticket' => array(
		'title'=>'ADMIN GIVE TICKET',
		'messages' => array(
			'active'=>'Giving ticket...',
			'success'=>'Gave ticket',
			'error'=>'There was an error while giving ticket.'
		),
		'permission'=> function($model) {
			return Auth::user()->can('grant_ticket');
		},
		'action'=> function($model) {
			$model->lans()->attach(Lan::curLan(), ['ticket_id' => str_random(40), 'gseatnum'=>null]);
			$model->save();
			return true;
		}
	),
	'unseat_user' => array(
		'title'=>'Remove user from seat',
		'messages' => array(
			'active'=>'Removing user from seat...',
			'success'=>'User unseated',
			'error'=>'There was an error while unseating user.'
		),
		'permission'=> function($model) {
			return Auth::user()->can('unseat_user');
		},
		'action'=> function($model) {
			$lan_id = Lan::curLan()->id;
			$lu = LanUser::where('user_id','=',$model->id)->where('lan_id','=',$lan_id)->first();
			if (!$lu) { 
				return false;
			} else {
				$lu->seat_group_id = null;
				$lu->seat_num = null;
				$lu->save();
				Cache::forget('seating');
				return true;
			}
			return true;
		}
	)
)

);
