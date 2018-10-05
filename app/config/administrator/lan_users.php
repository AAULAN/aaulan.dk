<?php

return array(
'title'=>'Lan Users',
'single'=>'lan user',
'model'=>'LanUser',
'columns'=>array(
	'id'=> array('title'=>'Id'),
	'user_id'=> array('title'=>'User','relationship'=>'user','select'=>"if((:table).display_name != '',(:table).display_name,(:table).name)"),
	'lan_id'=> array('title'=>'Lan','relationship'=>'lan','select'=>'(:table).name'),
	'ticket_id'=> array('title'=>'Ticket'),
	'seat_group_id'=> array('title'=>'Seat group'),
	'seat_num'=> array('title'=>'Seat no.'),
        'gseatnum'=> array('title'=>'Global seat no.')
),
'edit_fields'=>array(
	'user'=>array('title'=>'User','type'=>'relationship','name_field'=>'display_sanitized'),
	'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
	'ticket_id'=>array('title'=>'Ticket','type'=>'text'),
	'seat_group_id'=>array('title'=>'Seat group','type'=>'number'),
	'seat_num'=>array('title'=>'Seat no.','type'=>'number')
),
'filters'=>array(
	'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
	'user'=>array('title'=>'User','type'=>'relationship','name_field'=>'display_sanitized'),
	'ticket_id'=>array('title'=>'Ticket'),
	'seat_group_id'=> array('title'=>'Seat group'),
	'seat_num'=> array('title'=>'Seat no.'),
        'gseatnum'=> array('title'=>'Global seat no.'),
),

'permission'=> function() {
	return Auth::user()->can('users_edit');
},

);
