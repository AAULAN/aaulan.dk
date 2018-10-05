<?php

namespace Aaulan\Email;
use Rhumsaa\Uuid\Uuid;
use User;
use ValidationToken;
use Log;
use URL;
use Mail;

class TicketActivated {
	
	public function fire($job, $data) {
		Log::debug(__CLASS__." job started",array('job'=>$job,'data'=>$data));
		
		
		Mail::send('emails.ticketactivated',$data,function($message) use ($data) {
			$message->to($data['email'],$data['name'])->subject("AAULAN ticket");
			$message->from('ticket@aaulan.dk','AAULAN Ticket Validation');
			
		});
		
		$job->delete();
	}
	
}
