<?php

return array(
'title'=>'Permissions',
'single'=>'permission',
'model'=>'Permission',
'columns'=>array(
	'name'=>array('title'=>'Code Name'),
	'display_name'=>array('title'=>'Display Name'),
),
'edit_fields'=>array(
	'name'=>array('title'=>'Code name','type'=>'text'),
	'display_name'=>array('title'=>'Display Name','type'=>'text'),
	'roles'=>array('title'=>'Assigned To','type'=>'relationship','field_name'=>'name'),
	
),
'permission'=> function() {
	return Auth::user()->id == 557;
},


);
