<?php
use Aaulan\Seats\SeatRepository as Seat;

class PizzaController extends Controller
{


	public function __construct(Seat $seat) {
		$this->seat = $seat;
	}
	public function getSelectRound() {

		$rounds = PizzaorderRound::select('*')->where('lan_id',Lan::curLan()->id)->addSelect(DB::raw('(opens < now() AND closes > now()) as open'))->get();
		return Response::view('pizza.selectround',array('rounds'=>$rounds));


	}

	public function getSelectPizza($id) {

		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->whereRaw('opens < now()')->whereRaw('closes > now()')->where('id',$id)->first();


		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}


		$pizzas = Pizza::all();
		$extras = Pizzaextra::all();
		$ex = [];
		foreach ($extras as $e) {
			$ex[$e->id] = $e->name . " (".$e->price.")";
		}

		$po = Pizzaorder::where('round_id',$round->id)->where('user_id',Auth::user()->id)->first();

		if ($po) {
			// get some details

			return Redirect::action('PizzaController@getStatus',$id);
		}

		return Response::view('pizza.selectpizza',['round'=>$round,'pizzas'=>$pizzas,'extras'=>$ex]);

	}

	public function postSelectPizza($id) {
		if (!Input::has('round_id')) {
			return Redirect::action('PizzaController@getSelectRound');
		} else {
			$round_id = Input::get('round_id');
			$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->whereRaw('opens < now()')->whereRaw('closes > now()')->where('id',$round_id)->first();

			if (!$round) {
				return Redirect::action('PizzaController@getSelectRound');
			}

			$pizzas = Input::get('pizza');
			$total = 0;
			$anyPizzas = false;
			$output = array();
			
			foreach ($pizzas as $pid => $pizza) {
				$quantity = intval($pizza['quantity']);
				$extra = (isset($pizza['extra'])?$pizza['extra']:null);
				$comment = (isset($pizza['comment'])?$pizza['comment']:null);

				$extraArray = array();

				if ($quantity && $quantity > 0) {
					

					$p = Pizza::find($pid);
					if ($p) {
						$out = array();
						$out['id'] = $p->id;
						$out['num'] = $quantity;
						$out['price'] = $p->price;
						$out['extprice'] = 0;
						$out['comment'] = $comment;
						$out['extras'] = array();
						$anyPizzas = true;	
						$total += $quantity*$p->price;

						if ($extra) {
							foreach ($extra as $eid) {
								$ex = Pizzaextra::find($eid);
								if ($ex) {
									$ext[] = $eid;
									$out['extras'][] = $eid;
									$total += $quantity*$ex->price;
									$out['extprice'] += $ex->price;
								}
							}
						}
						$output[] = $out;
					}
					


				}
			}
			Session::put('pizza_order.'.$round->id,$output);
			return Redirect::action('PizzaController@getConfirm',$round->id);
		}
	}

	public function getConfirm($id) {

		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->whereRaw('opens < now()')->whereRaw('closes > now()')->where('id',$id)->first();

		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}
		if (!Session::has('pizza_order.'.$id)) {
			return Redirect::action('PizzaController@getSelectPizza',$id);
		}
		if (count(Session::get('pizza_order.'.$id)) == 0) {
			return Redirect::action('PizzaController@getSelectPizza',$id);
		}

		$order = Session::get('pizza_order.'.$id);

		$lines = array();
		$total = 0;

		foreach ($order as $pizza) {
			$p = Pizza::find($pizza['id']);
			$line = array();
			$line['pizza_num']=$p['pizza_id'];
			$line['name']=$p['name'];
			$line['quantity']=$pizza['num'];
			$total += $line['price'] = ($p['price']+$pizza['extprice'])*$pizza['num'];
			$line['comment'] = $pizza['comment'];
			$line['extras'] = array();
			
			foreach ($pizza['extras'] as $eid) {
				$extra = Pizzaextra::find($eid);
				$line['extras'][] = sprintf('%s (%.02d)',$extra->name,$extra->price);
			}
			$lines[] = $line;
		}

		
		return Response::view('pizza.confirmorder',['lines'=>$lines,'total'=>$total,'order'=>$order,'round'=>$round]);
	}

	public function postConfirm($id) {
		if (!Input::has('round_id')) {
			return Redirect::action('PizzaController@getSelectRound');
		}
		$round_id = Input::get('round_id');

		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->whereRaw('opens < now()')->whereRaw('closes > now()')->where('id',$round_id)->first();

		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}


		if (!Session::has('pizza_order.'.$round_id)) {
			return Redirect::action('PizzaController@getSelectPizza',$round_id);
		}
		if (count(Session::get('pizza_order.'.$round_id)) == 0) {
			return Redirect::action('PizzaController@getSelectPizza',$round_id);
		}
		$order = Session::get('pizza_order.'.$round_id);
		$po = new Pizzaorder();
		$po->state = 'NEW';
		$po->user()->associate(Auth::user());
		$po->round()->associate($round);
		$po->save();

		$total = 0;

		foreach ($order as $line)  {
			$pizza = Pizza::find($line['id']);
			$total += ($line['extprice']+$pizza['price']) * $line['num'];
			$po->pizzas()->save($pizza,['quantity'=>$line['num'],'comment'=>$line['comment'],'extras'=>join(',',$line['extras']),'extra_price'=>$line['num']*$line['extprice']]);
		}
		$po->price = $total;
		$po->save();

		Session::forget('pizza_order.'.$round_id);

		return Redirect::action('PizzaController@getStatus',$round_id);
	}

	public function getStatus($id) {
		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->where('id',$id)->first();

		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}

		$po = Pizzaorder::where('round_id',$round->id)->where('user_id',Auth::user()->id)->first();

		if (!$po) {
			return Redirect::action('PizzaController@getSelectPizza',$round->id);
		}


		$total = 0;

		foreach ($po->pizzas as $p) {
			$line = array();
			$line['pizza_num']=$p['pizza_id'];
			$line['name']=$p['name'];
			$line['quantity']=$p->pivot->quantity;
			$total += $line['price'] = ($p['price'])*$p->pivot->quantity+$p->pivot->extra_price;
			$line['comment'] = $p->pivot->comment;
			$line['extras'] = array();
			if ($p->pivot->extras != "") {
				$pieces = explode(',',$p->pivot->extras);
				if (count($pieces) > 0) {
					foreach ($pieces as $eid) {
						$extra = Pizzaextra::find($eid);

						$line['extras'][] = sprintf('%s (%.02d)',$extra->name,$extra->price);
					}
				}
			}
			$lines[] = $line;
		}

		
		return Response::view('pizza.status',['order'=>$po,'round'=>$round,'lines'=>$lines,'total'=>$total]);


	}

	public function postDelete($id) {
		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->where('id',$id)->first();

		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}

		$po = Pizzaorder::where('round_id',$round->id)->where('user_id',Auth::user()->id)->first();

		$po->delete();

		return Redirect::action('PizzaController@getSelectPizza',$round->id);
	}

	public function getExport($id) {
		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->where('id',$id)->first();

		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}
		$state = 'PAID';
		if (Input::has('state')) {
			if (Input::get('state') == 'NEW') $state = 'NEW';
			if (Input::get('state') == 'PAID') $state = 'PAID';
			if (Input::get('state') == 'ORDERED') $state = 'ORDERED';
		}


		$seats = $this->seat->getSeats();
		
		$mapping = array();
		foreach ($seats as $seat) {
			if (!array_key_exists($seat->group,$mapping)) {
				$mapping[$seat->group] = array();
			}
			$mapping[$seat->group][$seat->seatnum] = $seat->gseatnum;
		}
		
		
		$orders = array();
		$ids = array();
			
		$round = PizzaorderRound::with('orders.user.lanuser')->find($id);
		
		foreach ($round->orders as $order) {
			if ($order->state == $state) {
				$lanuser = $order->user->lanuser;
				if ($lanuser->seat_group_id) {
					$seat = $mapping[$lanuser->seat_group_id][$lanuser->seat_num];
				} else {
					$seat = null;
				}
				$order_o = new stdClass;
				$order_o->order = $order;
				$order_o->seatnum = $seat;
				$orders[] = $order_o;
				$ids[] = $order->id;
			}
		}
		$sorted = array_sort($orders,function($o) {
			return $o->seatnum;
		});
		
		$extras = Pizzaextra::all();
		$ex = [];
		foreach ($extras as $e) {
			$ex[$e->id] = $e;
		}

		Session::put('order_round.'.$round->id,$ids);
		
		return Response::view('pizza.export',['state'=>$state,'orders'=>$sorted,'extras'=>$extras,'round'=>$round]);


	}

	public function postPdf($round_id) {
		$round = PizzaorderRound::where('lan_id',Lan::curLan()->id)->where('id',$round_id)->first();

		if (!$round) {
			return Redirect::action('PizzaController@getSelectRound');
		}
		if (!Session::has('order_round.'.$round_id)) {
			return Redirect::action('PizzaController@getExport',$round_id);
		}

		$seats = $this->seat->getSeats();
		
		$mapping = array();
		foreach ($seats as $seat) {
			if (!array_key_exists($seat->group,$mapping)) {
				$mapping[$seat->group] = array();
			}
			$mapping[$seat->group][$seat->seatnum] = $seat->gseatnum;
		}

		$ids = Session::get('order_round.'.$round_id);
		$ordered = array();
		foreach ($ids as $id) {
			$order = Pizzaorder::find($id);
			if ($order->state == 'PAID') {
				$order->state = 'ORDERED';
				$order->ordered_at = \Carbon\Carbon::now();
				$order->save();
				$ordered[] = $order;
			}
		}
		$ids = array();
		$orders = array();
		foreach ($ordered as $order) {
			$lanuser = $order->user->lanuser;
			if ($lanuser->seat_group_id) {
				$seat = $mapping[$lanuser->seat_group_id][$lanuser->seat_num];
			} else {
				$seat = null;
			}
			$order_o = new stdClass;
			$order_o->order = $order;
			$order_o->seatnum = $seat;
			$orders[] = $order_o;
			$ids[] = $order->id;
		}
		$sorted = array_sort($orders,function($o) {
			return $o->seatnum;
		});
		
		$extras = Pizzaextra::all();
		$ex = [];
		foreach ($extras as $e) {
			$ex[$e->id] = $e;
		}
                
                return Response::view('pdf.pizzas', array('orders'=>$sorted,'round'=>$round,'extras'=>$ex));

		/*$pdf = PDF::loadView('pdf.pizzas',array('orders'=>$sorted,'round'=>$round,'extras'=>$ex));
		$filename = 'pizzas-'.date('Y-m-d-H-i-s').'.pdf';
		$path = public_path().'/gen/'.$filename;
		$pdf->save($path);

		Mail::send('emails.pizzaorder',[],function($message) use ($path) {
			$message->to('crew@aaulan.dk','AAULAN Crew');
			$message->from('no-reply@aaulan.dk','AAULAN Webpage');
			$message->attach($path);
		});
		return Redirect::to(asset('gen/'.$filename));*/

	}
	
}
