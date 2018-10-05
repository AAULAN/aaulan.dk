@extends('layouts.master')

@section('content')

<div class="row">
<div class="col-md-4">
@if ($message)
<div class="alert alert-info">{{$message}}</div>
@endif
@if ($error)
<div class="alert alert-danger">{{$error}}</div>
@endif
<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Activate your ticket!</h4></div>
	<div class="panel-body">

<p>Enter your ticket number to get access to seat reservation.</p>


@if (!$hasAdmission)
{{Form::open(['action'=>'TicketController@postSignUp'])}}
{{$message}}
<div class="form-group">
{{Form::label('ticketnumber','Ticket number (10 digits and letters)')}}
{{Form::text('ticketnumber',null,array('class'=>'form-control'))}}
{{ $errors->first('ticketnumber')}}
</div>

{{ Form::submit('Activate',array('class'=>'btn btn-primary'))}}
</form>
@else
<p>You have already activated a ticket on this website for the next Lan-party!</p>
@endif

</div>
</div>
</div>
</div>

@stop
