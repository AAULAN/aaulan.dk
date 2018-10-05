@extends('layouts.master')

@section('content')
<div class="row"><div class="col-md-4">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Send password reminder</h4></div>
  <div class="panel-body">

{{ Form::open(['action'=>'RemindersController@postRemind'])}}
{{$error}}
{{$status}}
<div class="form-group">
{{Form::label('Please enter your e-mail address')}}
{{ Form::text('email',null,array('class'=>'form-control'))}}
</div>
{{ Form::submit('Reset my password',array('class'=>'btn btn-success'))}}
{{ Form::close()}}
</div></div></div></div>
@stop
