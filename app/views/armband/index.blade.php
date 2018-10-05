@extends('layouts.master')

@section('content')

{{Form::open(['action'=>'ArmbandController@search'])}}
<div class="panel panel-default">
<div class="panel-heading">Armband handout</div>
<div class="panel-body">
<div class="form-group">
<label>Seat number search:</label>
{{Form::text('seatnum',null,['class'=>'form-control'])}}
</div>

{{Form::submit('Search',['class'=>'btn btn-default'])}}
</div>
</div>
{{Form::close()}}


@if ($result)
<div class="panel panel-default">
<div class="panel-heading">Result</div>
<div class="panel-body">
<p>Found {{$result->user->name}}</p>
</div>
</div>

@endif


@stop
