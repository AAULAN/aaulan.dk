<?php

		use Illuminate\Database\Eloquent\ModelNotFoundException;

use Aaulan\Seats\SeatRepository as Seat;
class UserController extends BaseController {
	
	public function __construct(Seat $seat) {
		$this->seat = $seat;
	}
	
	public function getLogin() {
		
		return View::make('login.form',['error'=>Session::get('error')]);
		
	}
	
	public function postLogin() {

		if (Auth::attempt(['email'=>Input::get('email'),'password'=>Input::get('password')],Input::get('remember'))) {
			Session::put('user_password',Input::get('password'));
			return Redirect::intended('/');
		} else {
			Notification::danger('The credentials you have entered are invalid.');
			return Redirect::action('UserController@getLogin');
		}
	}
	
	public function getRegister() {
		
		if (Auth::check()) {
			return Redirect::to('/');
		}
		
		return Response::view('user.register');
		
	}
	
	public function postRegister() {
		$msg = array('aau_mail'=>'Please use your student e-mail.');
		Validator::extend('aau_mail',function($attribute,$value,$parameters) {
			return (substr(strtolower($value),-7) == '.aau.dk') || (substr(strtolower($value),-7) == '@aau.dk');
		});

		$validator = Validator::make(Input::all(),[
			'email'=>'required|email|unique:users,email',
			'name'=>'required',
			'password'=>'required|min:8|confirmed',
			'display_name'=>'max:25|unique:users,display_name',
			'phone'=>'numeric',
			'ida'=>'numeric'
			
		],$msg);
		
		if ($validator->fails()) {
			return Redirect::action('UserController@getRegister')->withInput()->withErrors($validator);
		} else {
			$user = new User(Input::only('email','name','display_name','phone','ida'));
			$user->password = Input::get('password');
			Sluggable::make($user,true);
			$user->save();
			Log::info('User created',$user->toArray());
			Queue::push('Aaulan\Email\SendValidationEmail',$user->id);
			Auth::login($user);
			Notification::success('Your account has been created, but it must be validated before it can be used. Please check your e-mail account for instructions on how to activate it.');
			return Redirect::to('/');
		}
	}
	
	public function getProfile() {
		
		$user = Auth::user();
		$yourteams = Auth::user()->teams()->with(array('users'=>function($query) {
			$query->where('accepted','=',1);
		}))->get();
		$allteams = Team::with(array('users'=>function($query) {
			$query->where('accepted','=',1);
		}))->get();
		$requests = TeamUser::with('user','team')->where('accepted','=',0)->get()->filter(function($teamuser) {
			return ($teamuser['team']['user_id'] == Auth::user()->id);
		});
		
		return View::make('user.profile',['user'=>$user,'message'=>Session::get('message'),'requests'=>$requests,'yourteams'=>$yourteams,'allteams'=>$allteams]);
		
	}
	
	public function postProfile() {
		

		$validator = Validator::make(Input::all(),[
			'name'=>'required',
			'password'=>'min:8|confirmed',
			'display_name'=>'max:25|unique:users,display_name,'.Auth::user()->id,
			'phone'=>'numeric',
			'ida'=>'numeric',

			
		]);
		
		if ($validator->fails()) {
			return Redirect::action('UserController@getProfile')->withInput()->withErrors($validator);
		} else {
			$user = Auth::user();
			if ($pass = Input::get('password')) {
				$user->password = Input::get('password');
			}
			$canUpdateSummonerName = true;
			foreach ($user->teams as $team) {
				foreach ($team->tournaments as $tournament) {
					if ($tournament->require_riot_summoner_name) {
						$canUpdateSummonerName = false;
						break;
					}
				}
			}

			$queueRiotRefresh = false;
			$get_summoner_name = Input::get('riot_summoner_name');
			if ($user->riot_summoner_name != $get_summoner_name || $user->riot_status == NULL) {

				if ($canUpdateSummonerName) {
					$user->riot_summoner_name = $get_summoner_name;
					if (!empty($get_summoner_name)) {
						$queueRiotRefresh = true;
						$user->riot_status = 'REFRESH NEEDED';
						$user->riot_message = 'Refresh has been queued. Please be patient.';
					} else {
						$user->riot_status = null;	
					}
					
					
					$user->riot_message = null;
					$user->riot_tier = null;
					$user->riot_division = null;
					$user->riot_league_points = null;
					$user->riot_summoner_id = null;

					if ($queueRiotRefresh) {
						Queue::push('Aaulan\Riot\GetRiotStats',Auth::user()->id);
					}


				} else {
					Notification::danger('You cannot change your summoner name, as you are part of a team that is currently signed up for a League of Legends tournament.');
				}


				
			}
			$user->fill(Input::only('name','display_name','phone','ida'));
			if ($user->slug == "") {
				Sluggable::make($user,true);
			}			
			$user->save();

			if ($queueRiotRefresh) {
				Queue::push('Aaulan\Riot\GetRiotStats',Auth::user()->id);
			}

			Notification::success('Your profile has been updated!');
			return Redirect::action('UserController@getProfile');
			
		}
		
	}
	
	
	
	public function getShowProfile(User $user) {
		
		$seating = $this->seat->getSeats();
		$name = $user->display_sanitized;
		
		$seat = "";
		$seats = array_where($seating,function($key,$value) use ($name) {
			if ($value->user) {
				if ($value->user->name == $name) {
					return true;
				}
			}
		});
		$seat = head($seats);
		
		
		$lans = LanUser::where('user_id', '=', $user->id)->whereNotNull('ticket_id')->get();
		
		return Response::view('user.view',array('user'=>$user,'seat'=>$seat, 'lans'=>$lans));
	}

	public function getUsers() {
		$users = User::with('lans')->paginate(25);
		return View::make('admin.user.list',['users'=>$users,'message'=>Session::get('message')]);
	}

	public function logout() {
		Auth::logout();
		return Redirect::action('UserController@getLogin');
	}

	public function getValidateLink($token) {
		$vt = ValidationToken::where('token','=',$token)->first();
		if ($vt) {
			$user = $vt->user;
			$user->validated = 1;
			$user->save();
			$user->validation()->delete();
			return Redirect::to(URL::action('UserController@getProfile'))->with('gmessage',array('type'=>'success','message'=>'You have successfully validated your account.'));
		} else {
			Notification::danger('Validation token was not found. Maybe your ticket was already validated?');
			return Redirect::action('HomeController@showWelcome');
		}
	}

	public function getSendValidationEmail() {
		Queue::push('Aaulan\Email\SendValidationEmail',Auth::user()->id);
		return Response::view('user.sendvalidation',Auth::user());
	}
	
	public function adminGetContracts() {
		$usersSeated = array();
		$contracts = array();
		$seats = $this->seat->getSeats();
		
		foreach ($seats as $seat) {
			if (!$seat->user) continue;
			$usersSeated[] = $seat->user->id;
			
			$contract = new stdClass;
			$contract->user = User::find($seat->user->id);
			$contract->seatnum = $seat->gseatnum;
			$contracts[] = $contract; 
		}
		// remaining users
		$lus = LanUser::with('User')->whereNotIn('user_id',$usersSeated)->get();
		foreach ($lus as $lu) {
			$contract = new stdClass;
			$contract->user = $lu->user;
			$contract->seatnum = null;
			$contracts[] = $contract;
		}
		$fp = fopen('php://output','w');
		fputcsv($fp,array('Name','IDA','SeatNumber'));
		foreach ($contracts as $c) {
			fputcsv($fp,array($c->user->name,$c->user->ida,$c->seatnum));
		}
		fclose($fp);
	}

	public function postTransferTicket() {
		$receiver = Input::get('receiver');
		$user = User::where('email','=',trim($receiver))->first();
		if (!$user) {
			Notification::info($receiver.' was not found! Nothing transferred.');
			return Redirect::action('UserController@getProfile');
		}

		if ($user->hasAdmission()) {
			Notification::info($user->email.' already has a ticket! Nothing transferred.');
			return Redirect::action('UserController@getProfile');
		}
		$lan = Lan::curLan();
		$cur = Auth::user();
		$lanuser = LanUser::whereUserId($cur->id)->whereLanId($lan->id)->first();
		if (!$lanuser) {
			Notification::info('Your ticket was not found! Nothing transferred.');
			return Redirect::action('UserController@getProfile');
		}
		$lanuser->user_id = $user->id;
		if ($lanuser->save()) {
			Slack::to('#website')->send(sprintf("User %s transferred ticket to %s",$cur->email,$user->email));
			Notification::success('You successfully transferred your ticket.');
			return Redirect::action('UserController@getProfile');
		} else {
			Slack::to('#website')->send(sprintf("Failed! User %s could not transfer ticket to %s",$cur->email,$user->email));
			Notification::danger('An error occurred while transferring your ticket! Nothing transferred. Contact crew.');
			return Redirect::action('UserController@getProfile');
		}

	}
	
}
