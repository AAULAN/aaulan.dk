@extends('layouts.master')

@section('content')
<?php $i = 0; ?>
<div class="row">
	<div class="col-md-8">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">{{$tournament['game']}}</h4></div>
  <div class="panel-body">
		<div>
			{{Markdown::render($tournament->description)}}
		</div>
	</div>
<table class="table">
	<thead>
		<tr>
			<th>#</th>
			@if ($tournament['players_per_team'] == 1)
			<th>Player name</th>
			<th></th>
			@else
			<th>Team name</th>
			<th class="hidden-xs">Players</th>
			<th></th>
			@endif
			
		</tr>
	</thead>
<tbody>
@if ($tournament['players_per_team'] == 1)
@foreach($tournament->users as $u)
<tr>
	<td>{{ ++$i; }}</td>
	<td>{{$u->display}}</td>
	<td>
		@if ($u->id == Auth::id())
		<a href="{{URL::action('GamesController@getLeaveTournament',array('id'=>$tournament['id']))}}" class="btn btn-xs btn-danger">Leave</a>
		@endif
	</td>
</tr>
@endforeach
@else
@foreach($tournament->teams as $p)
<tr>
	<td>{{ ++$i; }}</td>
	<td><a href="{{URL::action('TeamController@getTeam',$p['id'])}}">{{$p['name']}}</a></td>
	<td class="hidden-xs">
		@foreach ($p->users as $u)
		<p>

			{{$u->getDisplayAttribute()}}
			@if (Auth::user()->can('users'))	
				@if ($number = $u->seatNumber())
					(<a href="{{url('seating')}}#{{$number}}">{{$number}}</a>)
				@endif
			@endif
			@if ($tournament->require_riot_summoner_name)
			(<a target="_blank" href="http://lolking.net/search?name={{rawurlencode($u->riot_summoner_name)}}&amp;region=EUW">{{$u->riot_summoner_name}}</a> {{$u->riot_tier}} {{$u->riot_division}})
			@endif

		</p>
		@endforeach
	</td>
	<td>
		@if (array_key_exists($p['id'],$yourteams) && $tournament->signup_open)
		<a href="{{URL::action('GamesController@getLeaveTournament',array('id'=>$tournament['id'],'teamid'=>$p['id']))}}" class="btn btn-xs btn-danger">Leave</a>
		@endif
	</td>
</tr>
@endforeach
@endif
</tbody>
</table>
</div>
</div>
<div class="col-md-4">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Sign up</h4></div>
  <div class="panel-body">
	@if (!$tournament->signup_open)
		<p>Signup is closed.</p>
	@else
		@if ($joined)
			<p>You are signed up.</p>
		@else
			@if ($tournament->team_limit > 0 && count($tournament->users) + count($tournament->teams) >= $tournament->team_limit)
				<p>Team limit reached.</p>
			@else
				@if ($tournament->players_per_team == 1)
					{{Form::open(array('action'=>array('GamesController@postJoinTournament',$tournament['id'])))}}
					
					{{Form::submit('Sign up!',array('class'=>'btn btn-primary'))}}
					
					{{Form::close()}}

				@else
					@if (count($yourteams) > 0)
						{{Form::open(array('action'=>array('GamesController@postJoinTournament',$tournament['id'])))}}
						<div class="form-group">
							{{Form::label('Team')}}
							{{Form::select('team',$yourteams,null,array('class'=>'form-control'))}}
							
						</div>
						
						{{Form::submit('Sign up!',array('class'=>'btn btn-primary'))}}
						
						{{Form::close()}}
					@else
						<p>You do not have any teams.</p>
					@endif
				@endif
			@endif
		@endif
	@endif
</div>
</div>
</div>
</div>

@stop
