@extends('layouts.master')

@section('content')

<?php
// todo: move to controller, quick fix
$user = Auth::user();
$admission = isset($user) && $user->hasAdmission();
$userId = isset($user) ? $user->id : -1;
?>

@if (!$admission)
<div class="alert alert-alert">You must validate your ticket before choosing a seat.</div>
@endif

<div ng-controller="PickSeatController">
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Seating chart</h4></div>
  <div class="" style="overflow:auto;">

<div class="seating-chart">
	
	<div ng-repeat="seat in seats" tooltip="@{{seat.user.name}}" ng-click="{{ $admission ? 'chooseSeat(seat)' : 'alert(\'You need to buy a ticket for this!\');' }}" class="@{{seat.class}}" ng-class="{'table-yours':({{$userId}}==seat.user.id),'table-taken':(seat.user.id && {{$userId}}!=seat.user.id && seat.user.id!=446),'table-available':(!seat.user.id),'table-reserved':(seat.user.id == 446)}" data-gseatnum="@{{seat.gseatnum}}" style="top: @{{seat.ypos}}px; left:@{{seat.xpos}}px;"><p>@{{seat.gseatnum}}</p></div>
	
</div>


</div>
@if ($admission)
<div class="panel-footer"><a ng-click="unseatMe()" class="btn btn-danger">Unseat me</a></div>
@endif
</div></div>
@stop
