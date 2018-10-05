@extends('layouts.master')

@section('content')
<h1>Users</h1>
{{$message}}


<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Name</th>
			<th>Display Name</th>
			<th>Has ticket</th>
			<th>E-mail</th>
			<th>Phone</th>
			<th>IDA member no.</th>
			<th>SLUG</th>
			<th>Validated</th>
			<th>Created</th>
			<th>Updated</th>
			<th>Roles</th>
			<th>&nbsp;</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach($users as $user)
			<tr>
				<td>{{$user->id}}</td>
				<td>{{$user->name}}</td>
				<td>{{$user->display_name}}</td>
				<td>{{$user->hasAdmission()?'YES':'NO'}}</td>
				<td>{{$user->email}}</td>
				<td>{{$user->phone}}</td>
				<td>{{$user->ida}}</td>
				<td>{{$user->slug}}</td>
				<td>{{$user->validated?'YES':'NO'}}</td>
				<td>{{$user->created_at}}</td>
				<td>{{$user->updated_at}}</td>
				<td>{{join(', ',array_pluck($user->roles,'name'))}}</td>
				<td></td>				
			</tr>
		@endforeach
		
	</tbody>
	
</table>
{{$users->links()}}

@stop
