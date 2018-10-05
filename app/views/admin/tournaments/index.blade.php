@extends('layouts.master')

@section('content')
<h1>Games</h1>
<div class="row">
	<div class="col-md-8">
		<h2>Tournaments</h2>
<table class="table" ng-controller="AdminTournamentEditor">
	<thead>
	<tr>
		<th>Tournament</th>
		<th>Type</th>
		<th>Teams</th>
		<th>State</th>
		<th>Brackets</th>
		<th></th>
	</tr>
	</thead>
	<tbody>
@foreach ($tournaments as $t)
<tr>
	<td><a href="{{URL::action('GamesController@getTournament',array('id'=>$t['tournament']['id']))}}">{{$t['tournament']['name']}}</a></td>
	<td>{{$t['tournament']['tournament_type']}}</td>
		<td>{{$t['tournament']['participants_count']}}</td>
	<td>{{$t['tournament']['state']}}</td>
	<td><a href="{{$t['tournament']['full_challonge_url']}}">View</a></td>
	<td>
		
		@if ($t['tournament']['state'] == 'pending')
		
		<a ng-click="start({{$t['tournament']['id']}})" class="btn btn-xs btn-success">Start</a>
			
		@elseif ($t['tournament']['state'] == 'underway')

		<a ng-click="reset({{$t['tournament']['id']}})" class="btn btn-xs btn-warning">Reset</a>
		
		@elseif ($t['tournament']['state'] == 'awaiting_review')
		<a ng-click="finalize({{$t['tournament']['id']}})" class="btn btn-xs btn-success">Finalize</a>
		
		@endif
		<a ng-click="destroy({{$t['tournament']['id']}})" class="btn btn-xs btn-danger">Destroy</a>
		
		
		</td>
</tr>
@endforeach
</tbody>
</table>
</div>
<div class="col-md-4">
	<fieldset>
		<legend>Create tournament</legend>
		{{Form::open(array('action'=>'GamesController@adminPostTournament'))}}
		
		<div class="form-group">
			{{Form::label('Tournament name')}}
			{{Form::text('name',null,array('class'=>'form-control'))}}
			{{ $errors->first('name','<small class="text-warning">:message</small>')}}
		</div>
		
		<div class="form-group">
			{{Form::label('Tournament type')}}
			{{Form::select('type',array('single elimination'=>'single elimination','double elimination'=>'double elimination','round robin'=>'round robin','swiss'=>'swiss'),null,array('class'=>'form-control'))}}
		</div>
		
		{{Form::submit('Create',array('class'=>'btn btn-primary'))}}
		
		{{Form::close()}}
	</fieldset>
</div>

</div>
@stop
