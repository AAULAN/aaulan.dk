<?php

namespace Aaulan\Email;
use Rhumsaa\Uuid\Uuid;
use User;
use ValidationToken;
use Log;
use URL;
use Mail;

class SendTicketEmail {
	
	public function fire($job, $data) {
		Log::debug(__CLASS__." job started",array('job'=>$job,'data'=>$data));
		
		
		Mail::send('emails.ticket',$data,function($message) use ($data) {
			Log::debug(sprintf("TicketEmail to=%s",$data['to']), $data);
			$message->to($data['to'],$data['name'])->subject("AAULAN ticket");
			$message->from('ticket@aaulan.dk','AAULAN Ticket Validation');
			
		});
		
		$job->delete();
	}
	
}
