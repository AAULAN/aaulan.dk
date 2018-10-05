<?php

namespace Aaulan\Ticket;
use Rhumsaa\Uuid\Uuid;
use User;
use URL;
use Queue;
use Lan;
use Log;
use TicketValidationToken;
use Mail;
class CheckTicket {
	
	public function fire($job,$data) {
		Log::debug("CheckTicket job started",array('job'=>$job,'data'=>$data));
		/*
		 * $data[
		 * 'user_id',
		 * 'email',
		 * 'lan_id'
		 * ]
		 */
		 
		$user = User::findOrFail($data['user_id']);
		
		$apiUrl = "https://api.studentersamfundet.aau.dk/aaulan";
		
		$ch = curl_init();
        $query = http_build_query(array('ticketnumber'=>$data['ticketnumber'],'access_token'=>'064331c6072dbba10440dad974cc9d3b'));
        curl_setopt($ch, CURLOPT_URL, "$apiUrl?$query");
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
		// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));

		$cr = curl_exec($ch);
		
		if ($cr === FALSE) {
			Log::error('CheckTicket, curl error',curl_error($ch));
			//Slack::to('#error')->send('Error: '. curl_error($ch));
			// curl error
		} else {
			$json = json_decode($cr, true);
			//Slack::to('#error')->send('JSON: '. $json);
			if ($json ===  NULL) {
				Log::error('CheckTicket, json error: ' . $cr);
			} else {
				if ($json['TicketExists'] == true) {
					$user->lans()->attach(Lan::curLan(),['ticket_id'=>$data['ticketnumber']]);
					Log::info('CheckTicket, TicketActivated',$user->toArray());
					Queue::push('Aaulan\Email\TicketActivated',array('name'=>$user->name,'email'=>$user->email));
				} else {
					Log::info('CheckTicket, No ticket found in webshop',$user->toArray());
					Queue::push('Aaulan\Email\SendTicketEmail',array('result'=>0,'name'=>$user->name,'to'=>$user->email,'checked'=>$user->email,'user_email'=>$user->email));
				}
				
			}
		}
		
		
		
		$job->delete();
	}
	
}
