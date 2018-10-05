<?php

use Aaulan\Seats\SeatRepository as Seat;

class SlackController extends BaseController {

	public function __construct(Seat $seat) {
		$this->seat = $seat;
	}

	public function slashCommand() {

		$token = Input::get('token');
		if ($token != 'I22edNKxrCKsQwnni6HZnFM1' && $token != 'LsTHalNMtEdP6bF6dhx7knRC') {
			App::abort(404);
		}

		$command = Input::get('command');
		$text = Input::get('text');
		$pieces = explode(" ",$text);

		if ($command == '/seat') {
            $user = User::where('display_name', '=', $pieces[0])->first();
            $lanuser = LanUser::where('user_id', '=', $user->id)->where('lan_id', '=', Lan::curLan()->id)->first();
            return sprintf("Seated at %d", $lanuser->gseatnum);
            
			/*$num = $pieces[0];
			if (!is_numeric($num)) {
				return Response::make('Error: seatnum must be number',400);
			}

			$seats = $this->seat->getSeats();

			foreach ($seats as $seat) {
				if ($seat->gseatnum == $num) {
					if ($seat->user) {
						return sprintf("Seat #%d is occupied by %s.",$seat->gseatnum,$seat->user->name);
					} else {
						return sprintf("Seat #%d is not occupied.");
					}
				}
			}
			return Response::make('Error: seat not found?',404);*/
		}
		else if ($command == '/sales') {
                     return null;
		}

	}

}
