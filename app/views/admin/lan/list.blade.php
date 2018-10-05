@extends('layouts.master')

@section('content')
<h1>Lans</h1>
<p><a href="{{URL::action('LanController@getCreateForm')}}">Create new</a></p>
{{$message}}
{{Form::open(['action'=>'LanController@postSetActive','role'=>'form','class'=>'form-inline'])}}
<div class="form-group">
{{Form::label('id','Active lan')}}
{{Form::select('id',$lan_dropdown,null,['class'=>'form-control'])}}
</div>
{{Form::submit('Set as active',['class'=>'btn btn-default'])}}
{{Form::close()}}

<table class="table">
	<thead>
		<tr>
			<th>Is Active?</th>
			<th>ID</th>
			<th>Name</th>
			<th>Opens</th>
			<th>Closes</th>
			<th>Price</th>
			<th>Tickets</th>
			<th>Seats</th>
			<th>Created</th>
			<th>Updated</th>
			<th>&nbsp;</th>
			
		</tr>
	</thead>
	<tbody>
		@foreach($lans as $lan)
			<tr>
				<td>@if ($lan->active)YES@endif</td>
				<td>{{$lan->id}}</td>
				<td>{{$lan->name}}</td>
				<td>{{$lan->opens}}</td>
				<td>{{$lan->closes}}</td>
				<td>{{number_format($lan->price_member,2,',','.')}} ({{number_format($lan->price_nonmember,2,',','.')}})</td>
				<td>@if($lan->ticket_link != "")<a href="{{$lan->ticket_link}}" onclick="return false;">Link</a>@endif</td>
				<td>{{count($lan->users)}} / {{$lan->seats}}</td>
				<td>{{$lan->created_at}}</td>
				<td>{{$lan->updated_at}}</td>
				<td><a class="btn btn-primary btn-xs" href="{{URL::action('LanController@getEditForm',$lan->id)}}">Edit</a></td>
			</tr>
		@endforeach
		
	</tbody>
	
</table>

@stop
