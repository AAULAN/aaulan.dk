@extends('layouts.master')

@section('content')

<div class="alert alert-danger" role="alert">
	<strong>Important!</strong>
        Before placing a pizza order, please buy a pizza ticket for each pizza you want to purchase.
        Tickets are paid through Studendersamfundet's webshop. You can buy a pizza ticket at <a href="http://pizza.aaulan.dk" target="_blank">pizza.aaulan.dk</a> and then you must place an order here. 
</div>

<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Select day</h4></div>
	<div class="panel-body">
		<p>First select a day to order pizza for!</p>
	</div>
	<ul class="list-group">
		@foreach ($rounds as $round)
		<li class="list-group-item"><a href="{{URL::action('PizzaController@getSelectPizza',$round->id)}}">{{$round->name}}</a> @if($round->open == 0) (Too late!)@endif</li>
		@endforeach
	</ul>
</div>

@stop