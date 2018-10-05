@extends('layouts.master')

@section('content')
<?php
$curDay = null;
$now = \Carbon\Carbon::now('Europe/Amsterdam');
?>
<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Schedule</h4></div>
	<div class="panel-body">
		<a href="{{URL::action('ScheduleController@getGantt')}}" target="_blank" class="btn btn-primary">View schedule as a diagram</a>
	</div>
<table class="table table-condensed table-schedule">

	<tbody>
		@foreach ($items as $item)
		@if ($curDay != $item->starts->format('Y-m-d'))
		<tr class="@if ($now->format('Ymd') > $item->starts->format('Ymd')) passed @endif">
			<td colspan="3"><h3>{{$item->starts->format('l')}}</h3></td>
		</tr>
		<tr class="@if ($now->format('Ymd') > $item->starts->format('Ymd')) passed @endif">
			<th style="text-align:right; width:150px;">Time</th>
			<th>What</th>
		</tr>
		@endif
		<tr class="@if ($item->starts < $now && $item->ends && $item->ends > $now) current @elseif($item->starts < $now) passed @endif">
			<td style="text-align:right; width:150px;">{{$item->starts->format('H:i')}}@if ($item->ends)-{{$item->ends->format('H:i')}}@endif</td>
			<td><span tooltip-placement="left" tooltip="{{$item->description}}">{{$item->name}}</span></td>
		</tr>
		<?php $curDay = $item->starts->format('Y-m-d'); ?>
		@endforeach
		
	</tbody>
	
</table>
</div>

@stop
