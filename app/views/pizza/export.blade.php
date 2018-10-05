@extends('layouts.master')

@section('content')
{{Form::open(['action'=>['PizzaController@getExport',$round->id],'id'=>'theform','method'=>'get'])}}
	<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">{{$round->name}}</h4></div>
	<div class="panel-body">
		<p>Show me orders that are <select id="state_chooser" name="state" class="multiselect"><option value="-">Select here</option><option value="NEW">NEW</option><option>PAID</option><option>ORDERED</option></select>
		<button class="btn btn-primary btn-small">Show</button></p>
		<p>Now showing orders that are <strong>{{$state}}</strong></p>
	</div>

		<table class="table">
			<thead>
				<tr>
					<th>Seat</th>
					<th>Pizza</th>
					<th>Extra</th>
					<th>Comment</th>
					<th>User</th>
					<th>Price</th>
					<th>State</th>
					<th>Paid at</th>
					<th>Ordered at</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($orders as $order)
				@foreach ($order->order->pizzas as $pizza)
				@for ($i = 0; $i < $pizza->pivot->quantity; $i++)
				<tr>
					<td>{{$order->seatnum}}</td>
					<td>#{{$pizza->pizza_id}} {{$pizza->name}}</td>
					<td>
						@if ($pizza->pivot->extras)
						<?php $pieces = explode(',',$pizza->pivot->extras); ?>
						@if (count($pieces) > 0)
						
						@foreach ($pieces as $id)
							+{{$extras[$id]->name}}
						@endforeach
						
						@endif
						@endif
					</td>
					<td>{{$pizza->pivot->comment}}</td>
					<td>{{$order->order->user->name}}</td>
					<td>{{$pizza->price}} @if ($pizza->pivot->extra_price > 0) +{{$pizza->pivot->extra_price}} @endif</td>
					<td>{{$order->order->state}}</td>
					<td>{{$order->order->paid_at}}</td>

					<td>{{$order->order->ordered_at}}</td>

					
				</tr>
				@endfor
				@endforeach
				@endforeach
				
			</tbody>
			
		</table>
		</div>
		{{Form::close()}}

		@if ($state == 'PAID')
		{{Form::open(['action'=>['PizzaController@postPdf',$round->id]])}}
		<div class="panel panel-danger">
		<div class="panel-heading"><h4 class="panel-title">Export list</h4></div>
		<div class="panel-body">
		<p>Take the above listed orders and <strong>MARK THEM AS ORDERED</strong> and give me a PDF of those.</p>
		<p><button class="btn btn-danger">DO IT</button></p>
		</div>
		</div>
		@endif

{{Form::close()}}
@stop
@section('script')

@stop