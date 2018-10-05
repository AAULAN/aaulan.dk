<?php

return array(
'title'=>'Roles',
'single'=>'role',
'model'=>'Role',
'columns'=>array(
	'name'=>array('title'=>'Name'),
),
'edit_fields'=>array(
	'name'=>array('title'=>'Name','type'=>'text'),
	'permissions'=>array('title'=>'Permissions','type'=>'relationship','field_name'=>'display_name'),
	'users'=>array('title'=>'Users','type'=>'relationship','field_name'=>'name'),
),
'permission'=> function() {
	return Auth::user()->id == 557;
},


);
