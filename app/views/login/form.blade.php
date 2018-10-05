@extends('layouts.master')

@section('content')

<div class="row">
	
	
	<div class="col-md-4">


{{ Form::open(['action'=>'UserController@postLogin']) }}

<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Sign in</h4></div>
  <div class="panel-body">
@if ($error) <div class="alert alert-warning">{{ $error }}</div>@endif
<div class="form-group">
{{ Form::label('email','E-mail address') }}
{{ Form::text('email','',array('class'=>'form-control'))}}
</div>

<div class="form-group">
{{ Form::label('password','Password')}}
{{ Form::password('password',array('class'=>'form-control'))}}
</div>

<div class="checkbox">
<label>
{{Form::checkbox('remember',1)}}
	Keep me signed in.
</label>
</div>

{{ Form::submit('Sign in',array('class'=>'btn btn-success'))}}
<a href="{{URL::action('RemindersController@getRemind')}}" class="btn">I forgot my password</a>
</div></div>
{{ Form::close() }}
</div>
</div>
@stop
