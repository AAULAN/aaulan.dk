<?php

namespace Aaulan\Email;
use Rhumsaa\Uuid\Uuid;
use User;
use ValidationToken;
use Log;
use URL;
use Mail;

class SendValidationEmail {
	
	public function fire($job, $data) {
		Log::debug(__CLASS__." job started",array('job'=>$job,'data'=>$data));	
			
		// $data : user_id
		$user = User::find($data);
		if (!$user) {
			Log::error('SendValidationEmail could not find user with id '.$data);
		}
		
		// Create validation token
		$token = Uuid::uuid4();
		$user->validation()->save(new ValidationToken(array('token'=>$token)));	
		
		$emaildata = array(
			'name'=>$user->name,
			'validate_url'=>URL::action('UserController@getValidateLink',array('token'=>$token))
		);
		
		Mail::send('emails.validation',$emaildata,function($message) use ($user) {
			$message->to($user->email,$user->name)->subject("Validate your AAULAN Account");
			
		});
		
		$job->delete();
	}
	
}
