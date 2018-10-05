@extends('layouts.master')

@section('title', 'Tournaments')

@section('content')
<div class="row">
	<div class="col-md-8">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Tournaments</h4></div>

<table class="table">
	<thead>
	<tr>
		<th>Tournament</th>
		<th>Begins at</th>
		<th>Type</th>
		<th>Teams signed up</th>
	</tr>
	</thead>
	<tbody>
@foreach ($tournaments as $t)
<tr>
	<td><a href="{{URL::action('GamesController@getTournament',array('id'=>$t['id']))}}">{{$t['game']}}</a></td>
	<td>{{$t->begins_at}}</td>
	<td>{{sprintf('%1$dv%1$d',$t['players_per_team'])}}</td>
	<td>{{ (count($t->teams) + count($t->users)) . ($t->team_limit == 0 ? '' : ' / ' . $t->team_limit)}}</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>

</div>
@stop
