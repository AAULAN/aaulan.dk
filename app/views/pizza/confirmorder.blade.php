@extends('layouts.master')

@section('content')

<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Confirm order: {{$round->name}}</h4></div>
	<div class="panel-body">
		<p>Please confirm the following order for {{$round->name}}.</p>
	</div>
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
	<div class="panel-footer" style="overflow:hidden;">
	{{Form::open(['action'=>['PizzaController@postConfirm',$round->id]])}}
	{{Form::hidden('round_id',$round->id)}}
	
		<div class="pull-right"><button class="btn btn-success"><span class="glyphicon glyphicon-ok"></span> Confirm order</button></div>
	{{Form::close()}}
	</div>
</div>

@stop