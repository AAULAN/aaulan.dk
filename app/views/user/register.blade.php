@extends('layouts.master')

@section('content')
<div class="row">
	<div class="col-md-6">
{{ Form::open(array('action'=>'UserController@postRegister')) }}
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Register user</h4></div>
  <div class="panel-body">


<div class="form-group">
{{Form::label('email','E-mail address')}}
{{ Form::text('email',null,array('class'=>'form-control'))}}
{{ $errors->first('email','<small class="text-warning">:message</small>')}}
</div>

<div class="form-group">
{{Form::label('password','Password')}}
{{Form::password('password',array('class'=>'form-control'))}}
{{ $errors->first('password','<small class="text-warning">:message</small>')}}
</div>

<div class="form-group">
{{Form::label('password_confirmation','Confirm password')}}
{{Form::password('password_confirmation',array('class'=>'form-control'))}}
</div>

<div class="form-group">
{{Form::label('name','Full name')}}
{{ Form::text('name',null,array('class'=>'form-control'))}}
{{ $errors->first('name','<small class="text-warning">:message</small>')}}
</div>

<div class="form-group">
{{Form::label('display_name','Display name')}}
{{Form::text('display_name',null,array('class'=>'form-control'))}}
{{ $errors->first('display_name','<small class="text-warning">:message</small>')}}
</div>

<div class="form-group">
{{Form::label('phone','Phone number')}}
{{Form::text('phone',null,array('class'=>'form-control'))}}
{{ $errors->first('phone','<small class="text-warning">:message</small>')}}
</div>

<div class="form-group">
{{Form::label('ida','IDA membership no. (if applicable)')}}
{{Form::text('ida',null,array('class'=>'form-control'))}}
{{ $errors->first('ida','<small class="text-warning">:message</small>')}}
</div>

{{Form::submit('Create user',array('class'=>'btn btn btn-success'))}}

</div></div>
{{ Form::close() }}


</div>
</div>
@stop
