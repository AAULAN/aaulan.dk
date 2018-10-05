@extends('layouts.master')

@section('content')

@foreach ($lans as $lan)
<?php if (count($lan->winners) == 0) continue; ?>
<div class="panel panel-default">
  <div class="panel-heading">
	<h3 class="panel-title">{{$lan->name}}</h3>
  </div>
  <div class="panel-body">
<div class="row">
@foreach ($lan->winners->sortBy('place')->sortBy('game') as $winner)
<div class="col-sm-6 col-md-4 col-xs-12">
<a href="{{asset('uploads/originals/'.$winner->filename)}}" class="thumbnail" style="text-decoration:none">
<img src="{{asset('uploads/winners/'.$winner->filename)}}" alt="" />
<div class="caption">
	<h6>{{$winner->game}}</h6>
	<h6>{{$winner->place}}. place</h6>
	<p>{{$winner->team_name}}</p>
</div>
</a>
</div>

@endforeach
</div>
  </div>
</div>
@endforeach

@stop
