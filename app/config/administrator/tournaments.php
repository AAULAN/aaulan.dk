<?php

return array(
'title'=>'Tournaments',
'single'=>'tournament',
'model'=>'Tournament',
'form_width'=>800,
'columns'=>array(
  'id'=>array('title'=>'ID'),
  'game'=> array('title'=>'Game'),
  'players_per_team'=>array('title'=>'Players per team'),
  'require_riot_summoner_name'=>array('title'=>'Use LoL specific stuff','output'=>function($val) { return $val?'Yes':'No';}),
  'begins_at'=>array('title'=>'Begins at'),
  'signup_open'=>array('title'=>'Signup open','output'=>function($val) { return $val?'Yes':'No';}),
  'team_limit'=>array('title'=>'Team limit','output'=>function($val) { return $val ? $val : 'None';}), 
  'created_at'=>array('title'=>'Created at'),
  'updated_at'=>array('title'=>'Updated at'),
),
'edit_fields'=>array(
  'game'=>array('title'=>'Game','type'=>'text'),
  'description'=>array('title'=>'Text/messages','type'=>'markdown'),
  'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
  'players_per_team'=>array('title'=>'Players per team','type'=>'number','decimals'=>0,'thousands_seperator'=>'.','decimal_separator'=>','),
  'require_riot_summoner_name'=>array('title'=>'Use LoL specific stuff','type'=>'bool',),
  'begins_at'=>array('title'=>'Begins at','type'=>'datetime'),
  'signup_open'=>array('title'=>'Signup is open','type'=>'bool',),
  'team_limit'=>array('title'=>'Team limit'),
),
'filters'=>array(
  'lan'=>array('title'=>'Lan','type'=>'relationship','field_name'=>'name'),
  
),
'rules'=>array(
  'game'=>'required',
),
'permission'=> function() {
  return Auth::user()->can('tournaments');
},
'action_permissions'=>array(
  'delete' => function($model) {
    return Auth::user()->can('tournaments_edit');
  },
  'update' => function($model) {
    return Auth::user()->can('tournaments_edit');
  },
  'create' => function($model) {
    return Auth::user()->can('tournaments_edit');
  },
),
'link'=>function($model) {
  return URL::action('GamesController@getTeamList',$model->id);
}

);
