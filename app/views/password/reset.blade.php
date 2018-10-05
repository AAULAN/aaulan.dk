@extends('layouts.master')

@section('content')
<div class="row"><div class="col-md-4">
  <div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Reset password</h4></div>
  <div class="panel-body">

{{ Form::open(['action'=>'RemindersController@postReset'])}}
{{ Form::hidden('token',$token)}}
<div class="form-group">
{{ Form::label('email','E-mail address')}}
{{ Form::text('email',null,array('class'=>'form-control'))}}
</div>
<div class="form-group">
{{ Form::label('password','New password')}}
{{ Form::password('password',array('class'=>'form-control'))}}
</div>
<div class="form-group">
{{ Form::label('password_confirmation','Confirm password')}}
{{ Form::password('password_confirmation',array('class'=>'form-control'))}}
</div>
{{Form::submit('Reset password',array('class'=>'btn btn-success'))}}
{{Form::close()}}
</div></div>
</div></div>
@stop
