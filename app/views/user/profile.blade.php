@extends('layouts.master')

@section('content')

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading"><h4 class="panel-title">Profile information</h4></div>
			{{--<div class="panel-heading"><h4 class="panel-title"><a data-toggle="collapse" href="#collapseProfileInformation">Profile information</a></h4></div>--}}
			{{--<div id="collapseProfileInformation" class="panel-collapse collapse">--}}
			<div class="panel-body">
{{ Form::model($user,['action'=>'UserController@postProfile','class'=>'form-horizontal']) }}
@if ($message) <div class="alert alert-info">{{ $message }}</div>@endif


<div class="form-group">
{{Form::label('password','Password',['class'=>'col-sm-3 control-label'])}}
<div class="col-sm-9">{{Form::password('password',array('class'=>'form-control'))}}
{{ $errors->first('password','<small class="text-warning">:message</small>')}}</div>
</div>

<div class="form-group">
{{Form::label('password_confirmation','Confirm',['class'=>'col-sm-3 control-label'])}}
<div class="col-sm-9">{{Form::password('password_confirmation',array('class'=>'form-control'))}}</div>
</div>

<div class="form-group">
{{Form::label('name','Full name',['class'=>'col-sm-3 control-label'])}}
<div class="col-sm-9">{{ Form::text('name',null,array('class'=>'form-control'))}}
{{ $errors->first('name','<small class="text-warning">:message</small>')}}</div>
</div>

<div class="form-group">
{{Form::label('display_name','Display name',['class'=>'col-sm-3 control-label'])}}
<div class="col-sm-9">{{Form::text('display_name',null,array('class'=>'form-control'))}}
{{ $errors->first('display_name','<small class="text-warning">:message</small>')}}</div>
</div>

<div class="form-group">
{{Form::label('phone','Phone number',['class'=>'col-sm-3 control-label'])}}
<div class="col-sm-9">{{Form::text('phone',null,array('class'=>'form-control'))}}
{{ $errors->first('phone','<small class="text-warning">:message</small>')}}</div>
</div>
{{--
<div class="form-group">
{{Form::label('ida','IDA membership no. (if applicable)',['class'=>'col-sm-3 control-label'])}}
<div class="col-sm-9">{{Form::text('ida',null,array('class'=>'form-control'))}}
{{ $errors->first('ida','<small class="text-warning">:message</small>')}}</div>
</div>
--}}

</div>
<div class="panel-footer">
	<button class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Save</button>
	<button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Reset</button>
</div>
{{--</div>--}}
</div>
<div class="panel panel-default">
			<div class="panel-heading"><h4 class="panel-title">League of Legends</h4></div>
			
			<div class="panel-body">
				<p>To participate in the League of Legends tournament, you must enter your summoner name.</p>
	<div class="form-group">
	{{Form::label('riot_summoner_name','Summoner Name (EUW)')}}
	<div class="input-group">
		{{Form::text('riot_summoner_name',null,array('class'=>'form-control'))}}
		<span class="input-group-addon" >
			@if ($user->riot_status == 'OK')
			<span class="glyphicon glyphicon-ok" style="color:#669933" data-toggle="tooltip" title="{{$user->riot_message}}"></span>
			@elseif($user->riot_status == 'ERROR')
			<span class="glyphicon glyphicon-remove" style="color:#996633" data-toggle="tooltip" title="{{$user->riot_message}}"></span>
			@elseif($user->riot_status == 'CURL_ERROR')
			<span class="glyphicon glyphicon-remove" style="color:#996633" data-toggle="tooltip" title="Error occurred while communicating with riot servers."></span>
			@elseif($user->riot_status == 'REFRESH NEEDED')
			<span class="glyphicon glyphicon-refresh" data-toggle="tooltip" title="{{$user->riot_message}}"></span>
			@else
			<span class="glyphicon glyphicon-question-sign" data-toggle="tooltip" title="{{$user->riot_message}}"></span>
			@endif

		</span>
	</div>
	{{ $errors->first('riot_summoner_name','<small class="text-warning">:message</small>')}}
	
	</div>
	@if ($user->riot_status == 'OK')
	<div class="form-group">
		<label>League</label>
		<p>{{$user->riot_tier}} {{$user->riot_division}}</p>
	</div>
	<div class="form-group">
		<label>League points</label>
		<p>{{$user->riot_league_points}}</p>
	</div>
	
	@endif



</div>
<div class="panel-footer">
	<button class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Save</button>
	<button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Reset</button>

</div>
</div>

{{ Form::close() }}
@if (Auth::user()->hasAdmission())
{{Form::open(array('action'=>'UserController@postTransferTicket'))}}
<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Transfer your ticket</h4></div>
	
	<div class="panel-body">
		<p>If you're unable to participate and have sold your ticket to another person, you can then transfer it to him here.</p>
		<div class="form-group">
			{{Form::label('receiver','Login e-mail of recipient')}}
			{{Form::text('receiver',null,array('class'=>'form-control'))}}
		</div>
		<p>This action can only be reversed by the recipient.</p>
	</div>
	<div class="panel-footer">
		<button class="btn btn-danger"><span class="glyphicon glyphicon-ok"></span> Transfer!</button>
	</div>
</div>
{{ Form::close() }}
@endif

		<div class="panel panel-default">
			<div class="panel-heading"><h4 class="panel-title">Notifications</h4></div>
			
			<div class="panel-body">

	<p>Register with <a href="https://www.pushbullet.com">PushBullet</a> to receive notifications on your Android, iPhone or Windows device. After registering you can associate your account with PushBullet and we will be able to notify you with important information.</p>
	@if ($user->pushbullet_access_token)
	<div id="pushbullet_status"></div>
	@endif
	<div class="form-group">
		<a href="https://www.pushbullet.com/authorize?client_id=weEdudNBBEuW7b7WLnwTVKoFjve3L4PX&amp;redirect_uri=https://aaulan.dk/oauth/auth_complete&amp;response_type=code" class="btn btn-primary">Link with PushBullet</a>
	</div>
</div>
<div class="panel-footer">Notification system is experimental</div>
</div>

</div>
<div class="col-md-6">
	@if (count($requests))
	<div class="panel panel-default">

		<div class="panel-heading"><h4 class="panel-title">Requests</div>
		<div class="panel-body"><p>The following people have requested to join one of your teams.</p></div>
	
	<table class="table">
		<thead>
			<tr>
				<th>Team name</th>
				<th>User</th>
				<th></th>
			</tr>
		</thead>
		<tbody ng-controller="TeamRequestCtrl">
			@foreach ($requests as $tr)
			<tr>
				<td>{{$tr->team->name}}</td>
				<td>{{$tr->user->display}}</td>
				<td><a href="#" ng-click="acceptRequest({{$tr->id}})" class="btn btn-xs btn-success">Accept</a><a href="#" ng-click="declineRequest({{$tr->id}})" class="btn btn-xs btn-danger">Decline</a></td>
			</tr>
			@endforeach
		</tbody>
	</table>
	</div>
	@endif

	<div class="panel panel-default">

		<div class="panel-heading"><h4 class="panel-title">Teams you are on</div>
		<div class="panel-body"><p>The owner of the team will be able to sign you up for tournaments.</p></div>
		
	<table class="table">
		<thead>
			<tr>
				<th>Team name</th>
				<th class="hidden-xs">Members</th>
				<th></th>
			</tr>
		</thead>
		<tbody ng-controller="YourTeamsCtrl">
			@if (count($yourteams))
			@foreach ($yourteams as $team)
			<tr>
				<td>{{$team->name}}</td>
				<td class="hidden-xs" title="">{{join('<br />',array_pluck($team->users,'display'))}}</td>
				<td>
					@if ($team->pivot->accepted == 0)
					Awaiting approval
					@else
					<a href="#" ng-click="@if ($team->creator->id == $user->id)disbandTeam @else leaveTeam @endif({{$team->id}})" class="btn btn-xs btn-danger">@if ($team->creator->id == $user->id)Disband @else Leave @endif</a></td>
					@endif
			</tr>
			@endforeach
			@else
			<tr><td colspan="2">You're not currently part of any teams.</td></tr>
			@endif
		</tbody>
	</table>
	</div>
	<div class="panel panel-default">

		<div class="panel-heading"><h4 class="panel-title">Teams you can join</div>
		<div class="panel-body"><p>The owner of the team must approve your request before you're on the team.</p></div>
	<table class="table">
		<thead>
			<tr>
				<th>Team name</th>
				<th class="hidden-xs">Creator</th>
				<th class="hidden-xs">Members</th>
				<th></th>
			</tr>
		</thead>
		<tbody ng-controller="AllTeamsCtrl">
			@if (count($allteams))
			@foreach ($allteams as $team)
			@if (!$team->users->contains($user->id))
			<tr>
				<td>{{$team->name}}</td>
				<td class="hidden-xs">{{!empty($team->creator->display_name)?$team->creator->display_name:$team->creator->name}}</td>
				<td class="hidden-xs">{{join('<br />',array_pluck($team->users,'display'))}}</td></td>
				<td><a href="#" ng-click="joinTeam({{$team->id}})" class="btn btn-xs btn-success">Join</a></td>
			</tr>
			@endif
			@endforeach
			@else
			<tr><td colspan="2">No team exists.</td></tr>
			@endif
		</tbody>
	</table>
	</div>
	{{Form::open(array('action'=>'TeamController@postTeam','class'=>'form-horizontal'))}}
	<div class="panel panel-primary">

		<div class="panel-heading"><h4 class="panel-title">Create a team</div>
		<div class="panel-body">
			
			<div class="form-group">
				{{Form::label('name','Team name',['class'=>'control-label col-sm-3'])}}
				<div class="col-sm-9">{{Form::text('name',null,array('class'=>'form-control'))}}</div>
			</div>
			
			

		</div>
		<div class="panel-footer">
			<button class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Create</button>
			<button type="reset" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> Reset</button>
		</div>
		
	
	</div>
	{{Form::close()}}
</div>
</div>
@stop

@section('script')
<script type="text/javascript">
(function() {
	var pb = jQuery('#pushbullet_status');
	if (pb) {
		pb.load('{{URL::action("PushbulletController@getAuthStatus")}}');
	}

})();
</script>
@stop
