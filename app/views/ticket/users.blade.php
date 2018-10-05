@extends('layouts.master')
@section('content')
<h1>Members signed up yet</h1>
<table class="table">
	
	<thead>
		<tr>
			<th>Name</th>
			<th>Real name</th>
			<th>Seat no.</th>
		</tr>
	</thead>
	<tbody>
	@foreach ($users as $user)
		<tr>
			<td><a href="{{URL::action('UserController@getShowProfile',array('user'=>$user->slugOrId()))}}">@if ($user->display_name == "") {{ $user->name }} @else {{$user->display_name}} @endif</a></td>
			<td>@if ($user->display_name != "") {{ $user->name }} @endif</td>
			<td>{{$user->pivot->seat_id}}</td>
		</tr>
	@endforeach
		
	</tbody>
	
</table>
@stop
