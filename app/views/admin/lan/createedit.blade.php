@extends('layouts.master')

@section('content')
@if ($edit)
<h1>Edit LAN</h1>
{{Form::model($lan,['action'=>['LanController@postLan',$lan['id']]])}}
@else
<h1>Create LAN</h1>
{{Form::open(['action'=>'LanController@postLans','role'=>'form'])}}
@endif


<div class="form-group">
{{Form::label('name','Name')}}
{{Form::text('name',null,['class'=>'form-control'])}}
{{$errors->first('name')}}
</div>
<div class="form-group">
{{Form::label('description','Description')}}
{{Form::textarea('description',null,['class'=>'form-control'])}}
{{$errors->first('description')}}
</div>
<div class="form-group">
{{Form::label('opens','Opens')}}
{{Form::input('date','opens_date',null,['class'=>'form-control'])}}
{{Form::input('time','opens_time',null,['class'=>'form-control'])}}
{{$errors->first('opens')}}
</div>
<div class="form-group">
{{Form::label('closes','Closes')}}
{{Form::input('date','closes_date',null,['class'=>'form-control'])}}
{{Form::input('time','closes_time',null,['class'=>'form-control'])}}
{{$errors->first('closes')}}
</div>
<div class="form-group">
{{Form::label('price_member','Price for members')}}
{{Form::text('price_member',null,['class'=>'form-control'])}}
{{$errors->first('price_member')}}
</div>
<div class="form-group">
{{Form::label('price_nonmember','Price for nonmembers')}}
{{Form::text('price_nonmember',null,['class'=>'form-control'])}}
{{$errors->first('price_nonmember')}}
</div>
<div class="form-group">
{{Form::label('ticket_link','Link to tickets')}}
{{Form::text('ticket_link',null,['class'=>'form-control'])}}
{{$errors->first('ticket_link')}}
</div>
<div class="form-group">
{{Form::label('seats','Seats')}}
{{Form::input('number','seats',null,['class'=>'form-control'])}}
{{$errors->first('seats')}}
</div>
{{Form::submit(($edit?'Save':'Create lan'),['class'=>'btn btn-primary'])}}

{{Form::close()}}

@stop
