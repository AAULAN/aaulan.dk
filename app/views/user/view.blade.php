@extends('layouts.master')

@section('content')
<div class="row"><div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">@if ($user->display_name != ""){{$user->display_name}}@else{{$user->name}}@endif's Profile</h4></div>
	<div class="panel-body">
	@if ($seat)<p>This user is seated at seat no. <a href="{{URL::action('SeatController@getSeating')}}">{{$seat->gseatnum}}</a>.</p>@endif
	
	<p>
	@if (count($lans) == 0)
		This user has not attended any LANs yet. :-(
	@else
		This user has attended {{ count($lans) }} LANs!
		<ul>
		@foreach($lans as $lan)
			<li> {{ Lan::where('id', '=', $lan->lan_id)->first()->name }}
		@endforeach
		</ul>
	@endif
	
	</div>
	
</div>
</div>
<div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Teams user is playing in</div>
	<table class="table">
		<thead>
			<tr>
				<th>Team name</th>
				<th>Members</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($user->teams as $team)
			@if ($team->pivot->accepted == 1)
			<tr><td>
				{{$team->name}}
			</td>
			<td>
				{{join('<br />',array_pluck($team->users,'display'))}}
			</td>
			</tr>
			@endif
			@endforeach
		</tbody>
	</table>
</div>
</div>
</div>
@stop
