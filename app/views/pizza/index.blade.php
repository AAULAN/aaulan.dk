@extends('layouts.master')

@section('content')
{{Form::open(['action'=>'PizzaController@postOrder'])}}
{{Form::hidden('round_id',$round->id)}}
<div class="panel panel-default">
	<div class="panel-heading"><h4 class="panel-title">Select which day you want to order pizza for:</h4></div>
	<div class="panel-body">
		@if (count($rounds) > 1)
		<div class="form-group">
			<label>Order:</label>
			<select id="pizzaround_chooser" class="form-control">
			@foreach ($rounds as $r)
			<option value="{{$r->id}}"@if ($r->id == $round->id) selected="selected"@endif>{{$r->name}}</option>
			@endforeach
			</select>
		</div>
		@else
		{{$round->name}}
		@endif
	</div>
</div>

@if ($order && !$order->paid)
<div class="jumbotron" style="background-color:#333;">
	<h1>Your order is not paid!</h1>
	<p>Please go to the crew table to pay your order.</p>
	<h1>Order number: {{$order->id}}</h1>
</div>
@endif

<div class="panel panel-default">

	<div class="panel-heading"><h4 class="panel-title">Pizza</h4></div>
	<div class="panel-body">
		

@if ($order)
<p>You have already made an order. @if($order->paid == 0)If you would like to change it, make a new one below and the old one will be deleted.@endif</p>
<p>Your order contains:</p>
<ul>
@foreach ($order->pizzas as $p)
	<li>{{$p->pivot->quantity}}&times; #{{$p->pizza_id}}. {{$p->name}} @if ($p->pivot->comment != "") ({{$p->pivot->comment}}) @endif
		@if ($p->pivot->extras != "")
			(<strong>WITH:</strong>@foreach(explode(',',$p->pivot->extras) as $id)@if (isset($extras[$id])) {{$extras[$id]}}@endif@endforeach)
		@endif
		</li>
@endforeach
	
</ul>
<p>Total price: DKK {{ number_format($order->price,2,',','.') }}. <strong>Your order ID is {{$order->id}}, this is helpful when you're going to pay at the crew table.</strong> @if ($order->paid == 1) <strong>Your order is paid.</strong> @else <strong>Your order is NOT PAID.</strong>@endif</p>
@endif

<p>{{$message}}</p>
<p>Select the pizza(s) you would like to order below. This order closes at <strong>{{$round->closes->format('j/m-Y H:i')}}</strong>, which is {{$round->timeTillCloses()}}.</p>

<p><strong>THE FOLLOWING WILL BE AVAILABLE AT DELIVERY, FREE OF CHARGE</strong></p>
<ul>
	<li>CREME FRAICHE DRESSING</li>
	<li>CHILI</li>
	<li>HVIDLØG</li>
</ul>
@if (!$order || $order->paid == 0)
<p>The menucard is available here: <a href="http://klingen.dk/menukort/klingen1_norm_2013_09.jpg">Menu 1</a>, <a href="http://klingen.dk/menukort/klingen2_norm_2013_09.jpg">Menu 2</a></p>
	</div>


<div style="overflow:auto">
<table class="table">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th>#</th>
			<th>Name</th>
			<th>Price</th>
			<th>Add</th>
			<th>Comment</th>
		</tr>
	</thead>
	<tbody>
	@foreach($pizzas as $pizza)
		<tr>
			<td>{{Form::input('number','quantity['.$pizza->id.']',0,['style'=>'width:50px;','class'=>'form-control'])}}</td>
			<td>{{$pizza->pizza_id}}</td>
			<td>{{$pizza->name}}</td>
			<td>DKK {{number_format($pizza->price,2,',','.')}}</td>
			<td>
				{{Form::select('extra['.$pizza->id.'][]',$extras,null,array('class'=>'multiselect','multiple'=>'multiple'))}}
				</td>			
			<td>{{Form::text('comment['.$pizza->id.']',null,['class'=>'form-control'])}}</td>
		</tr>
	
	@endforeach
		
	</tbody>
</table>
</div>
<div class="panel-footer">{{Form::submit('Create order',array('class'=>'btn btn-primary'))}}</div>

</div>
{{Form::close()}}

@endif
@stop


@section('script')
<script type="text/javascript">
(function() {
	jQuery('#pizzaround_chooser').change(function() {
		location.replace('{{URL::action('PizzaController@getIndex')}}/'+$(this).val());
	});
})();
</script>
@stop