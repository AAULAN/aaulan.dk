<?php

class TicketController extends BaseController {
	
	public function getSignUp() {
		if (Lan::curLan() == null) {
			return Response::view('ticket.nolanparty');
		} else {
			return View::make('ticket.signup',['message'=>Session::get('message'),'error'=>Session::get('error'),'hasAdmission'=>Auth::user()->hasAdmission()]);
		}
	}
	
	public function postSignUp() {
		$validator = Validator::make(Input::only('ticketnumber'),[
			'ticketnumber'=>'required'
		]);
		if ($validator->fails()) {
			return Redirect::action('TicketController@getSignUp')->withErrors($validator);
		} else {
			
			// Check if email has been used already
			$lu = LanUser::where('lan_id','=',Lan::curLan()->id)->where('ticket_id','=',strtolower(Input::get('ticketnumber')))->first();
			if ($lu) {
				Notification::danger('This ticket has already been used to sign up for this AAULAN.');
				return Redirect::to(URL::action('TicketController@getSignUp'));	
			}
			
			// Push queue as this may take a while
			
			$data = array('user_id'=>Auth::user()->id,
			'ticketnumber'=>Input::get('ticketnumber'),
			'lan_id'=>Lan::curLan()
			);
			
			Queue::push('Aaulan\Ticket\CheckTicket',$data);
			Notification::info('We are attempting to validate your ticket, please be patient as this may take a few minutes. You will receive an e-mail, when your ticket has been validated.');
			return Redirect::to(URL::action('TicketController@getSignUp'));
		}
	}
	
	public function getUsers() {
		return View::make('ticket.users',['users'=>Lan::curLan()->users]);
	}
	
	public function getVerifyTicket($token) {
		$tvt = TicketValidationToken::where('token','=',$token)->first();
		if ($tvt) {
			$user = $tvt->user;
			
			$user->lans()->attach(Lan::curLan(),['ticket_id'=>$tvt->email]);
			
			$user->ticketvalidation()->delete();
			Notification::success('You have successfully activated your ticket.');
			return Redirect::to(URL::action('TicketController@getSignUp'));
			
		} else {
			Notification::danger('Ticket validation token was not found. Maybe your ticket was already validated?');
			return Redirect::action('HomeController@showWelcome');
		}
	}
	
}
