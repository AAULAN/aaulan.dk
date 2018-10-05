<html>
	<body>
		<h2>Orderlist for {{$round->name}}</h1>
		<table style="width:100%;" border="1">
			<thead>
				<tr>
					<th>Pizza</th>
					<th>Seat</th>
					<th>Name</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$sum = 0;
				$quantityArray = array();
				?>
				@foreach ($orders as $order)
					@foreach ($order->order->pizzas as $pizza)
						<?php
						$quantity = $pizza->pivot->quantity;
						$sum += $quantity;
						if (array_key_exists($pizza->pizza_id, $quantityArray))
							$quantityArray[$pizza->pizza_id] += $quantity;
						else
							$quantityArray[$pizza->pizza_id] = $quantity;
						?>
						@for ($i = 0; $i < $quantity; $i++)
							<tr>
								<td>#{{$pizza->pizza_id}} {{$pizza->name}}</td>
								<td>{{$order->seatnum}}</td>
								<td>{{$order->order->user->name}}</td>
							</tr>
						@endfor
					@endforeach
				@endforeach
				
			</tbody>
			
		</table>
		
		<h2>Summary</h2>
		<table style="width:100%;" border="1">
			<thead>
				<tr>
					<th>Number</th>
					<th>Quantity</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($quantityArray as $number => $quantity)
					<tr>
						<td>{{ $number }}</td>
						<td>{{ $quantity }}</td>
					</tr>
				@endforeach
				<tr>
					<td><b>SUM</b></td>
					<td>{{ $sum }}</td>
				</tr>
			</tbody>
		</table>
	</body>
</html>