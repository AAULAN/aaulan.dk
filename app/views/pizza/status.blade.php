@extends('layouts.master')

@section('content')
{{Form::open(['action'=>['PizzaController@postDelete',$round->id]])}}
<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">Pizza order: {{$round->name}}</h4></div>
<div class="panel-body">
@if ($order->state == 'NEW')
	<div class="text-center">
		<h1 class="glyphicon glyphicon-usd text-danger"></h1>
		<h4>UNPAID!</h4>
		<p>You must go to the crew table and pay your pizza order.</p>
	</div>
@elseif ($order->state == 'PAID')
	<div class="text-center">
		<h1 class="glyphicon glyphicon-ok-sign text-success"></h1>
		<h4>PAID!</h4>
		<p>Your order is paid.</p>
	</div>
@elseif ($order->state == 'ORDERED')
	<div class="text-center">
		<h1 class="glyphicon glyphicon-ok-sign text-success"></h1>
		<h4>PAID!</h4>
		<p>Your order is paid.</p>
	</div>
@endif

</div>
</div>
<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">Order details</h4></div>
<table class="table food-table">
<thead>
	<tr>
		<th>Number</th>
		<th>Name</th>
		<th>Quantity</th>
		<th>Price</th>
	</tr>

</thead>
<tbody>
@foreach ($lines as $line)
	<tr>
		<td style="font-size:24px;font-weight:bold;">#{{$line['pizza_num']}}</td>
		<td>{{$line['name']}}</td>
		<td>{{$line['quantity']}}</td>
		<td>DKK {{number_format($line['price'],2,',','.')}}</td>
	</tr>
@endforeach
</tbody>
<tfoot>
<tr>
<td colspan="2"><strong>Total</strong></td>
<td></td>
<td><strong>DKK {{number_format($total,2,',','.')}}</strong></td>
</tr>
</tfoot>
</table>
@if ($order->state == 'NEW')

<div class="panel-footer">

<button class="btn btn-danger">Delete my order</button> <span>If you want to change your order, you must delete your old order first.</span>

</div>

@endif
</div>
{{Form::close()}}

@stop