<?php


class ChatController extends BaseController {

	public function getFrame() {
		$user = Auth::user();
		$xmppPrebind = new XmppPrebind('aaulan.dk','http://127.0.0.1:5280/http-bind/','candy',false,false);
		$xmppPrebind->connect($user->id, Session::get('user_password'));
		$xmppPrebind->auth();
		$sessionInfo = $xmppPrebind->getSessionInfo();
		return Response::view('chat.frame',$sessionInfo);
	}
	
	public function getPrebind() {
return null;

	}

}
