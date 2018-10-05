@extends('layouts.master')

@section('content')

<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Select food for {{$round->name}}</h4></div>
	<div class="panel-body">
		<p>Now select the food you would like to order for {{$round->name}}. All pizza orders include tomato sauce and cheese.</p>
	</div>
{{Form::open(['action'=>['PizzaController@postSelectPizza',$round->id]])}}
{{Form::hidden('round_id',$round->id)}}
<div style="overflow:auto">
<table class="table food-table">
	<thead>
		<tr>
			<th>Number</th>
			<th>Name</th>
			<th>Price</th>
	 		<th>Quantity</th>
		</tr>
	</thead>
	<tbody>
	@foreach($pizzas as $pizza)
		<tr>
			<td style="font-size:24px;font-weight:bold;">#{{$pizza->pizza_id}}</td>
			<td>{{$pizza->name}}</td>
			<td>DKK {{number_format($pizza->price,2,',','.')}}</td>
			<!-- <td>
				{{Form::select('pizza['.$pizza->id.'][extra][]',$extras,null,array('class'=>'multiselect','multiple'=>'multiple'))}}
				</td> -->			
			<!-- <td>{{Form::text('pizza['.$pizza->id.'][comment]',null,['class'=>'form-control'])}}</td> -->
                        <td>{{Form::input('number','pizza['.$pizza->id.'][quantity]',0,['style'=>'width:75px;','class'=>'form-control'])}}</td>
		</tr>
	
	@endforeach
		
	</tbody>
</table>
</div>
<div class="panel-footer" style="overflow:hidden;">
<div class="pull-right">
@if (Session::has('pizza_order.'.$round->id) && count(Session::get('pizza_order.'.$round->id)) > 0)
	<a href="{{URL::action('PizzaController@getConfirm',$round->id)}}" class="btn btn-success">Continue with previous selected <span class="glyphicon glyphicon-chevron-right"></a></button>
@endif
	<button class="btn btn-primary">Next <span class="glyphicon glyphicon-chevron-right"></span></button>
	</div>
</div>


</div>
{{Form::close()}}

@stop
