@extends('layouts.master')

@section('content')
<div class="row"><div class="col-md-6">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">{{$team->name}}</h4></div>
  <div class="panel-body">
<p>This team consists of:</p>
<ul>
	@foreach ($team->users as $user)
	<li><a href="{{URL::action('UserController@getShowProfile',$user->slugOrId())}}">{{$user->display}}</a></li>
	@endforeach
</ul>
</div>
</div>
</div>
</div>
@stop
