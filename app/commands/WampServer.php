<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;
use Psr\Log\NullLogger;
use Thruway\Logging\Logger;

use Ratchet\Wamp\Exception;
use Thruway\Common\Utils;
use Thruway\Message\ActionMessageInterface;
use Thruway\Module\Module;
use Thruway\Peer\Client;
use Thruway\Result;
use Thruway\Session;
use Thruway\Authentication\AuthorizationManagerInterface;



use Thruway\Authentication\WampCraAuthProvider;


class UserDb implements \Thruway\Authentication\WampCraUserDbInterface
{

	public function get($authid) {
		if ($authid == 'internal') {
			return ['authid'=>'internal','key'=>'secretverygood','salt'=>null];
		}
		$user = User::find($authid);
		if (!$user) {
			return false;
		} else {
			return ['authid'=>$user->id,'key'=>hash_hmac('sha1',$user->password,'aaulan#123'),'salt'=>null];
		}
	}

}

/**
 * Class AuthorizationManager
 * @package Thruway\Authentication
 */
class AuthorizationManager extends Module implements AuthorizationManagerInterface {

	public function isAuthorizedTo(Session $session, ActionMessageInterface $actionMsg) {
		$authDetails = $session->getAuthenticationDetails();
		$authid = $authDetails->getAuthId();

		if ($authid == 0) {
			return true;
		} else {

			$action = $actionMsg->getActionName();
			$uri = $actionMsg->getUri();

			printf("AUTHID: %d\tACTION: %s\tURI: %s --> ",$authid,$action,$uri);
			$result = true;
			if ($action == 'subscribe') {
				if (preg_match('/^liveupdate\.(\d+)$/',$uri,$match)) {
					$result = ($match[1] == $authid);
				}

			} else if ($action == 'publish') {
				$result = false;
			} else if ($action == 'register') {
				$result = false;
			}
		}

		printf("%s\n",($result?'GRANTED':'DENIED'));

		return $result;
	}
}

class InternalWampClient extends \Thruway\Peer\Client {

	public function __construct() {
		parent::__construct("aaulan");
	}

	public function getLiveUpdates($args,$kwArgs,$details) {
		if (!isset($details->authid)) {
			return NULL;
		}
		
		$user_id = $details->authid;
		$user = User::find($user_id);
		if (!$user) {
			return NULL;
		}
		
		$updates = $user->liveUpdates()->with('poster')->get();
		$array = [];
		foreach ($updates as $update) {
			$array[] = [
				'id'=>$update->id,
				'message'=>$update->message,
				'poster'=>$update->poster->display_sanitized,
				'created_at'=>$update->created_at->getTimestamp()*1000
			];
		}

		return [$array];

	}

	public function getUnreadCount($args,$kwArgs,$details) {
		if (!isset($details->authid)) {
			return NULL;
		}
		
		$user_id = $details->authid;
		$user = User::find($user_id);
		if (!$user) {
			return NULL;
		}

		$updates = $user->liveUpdates()->whereNull('seen')->get();
		return [count($updates)];
	}

	public function clearUnreadCount($args,$kwArgs,$details) {
		if (!isset($details->authid)) {
			return NULL;
		}
		
		$user_id = $details->authid;
		$user = User::find($user_id);
		if (!$user) {
			return NULL;
		}
		DB::table('live_update_user')->where('user_id',$user_id)->update(['seen'=>DB::raw('now()')]);
		return true;
	}

	public function onSessionStart($session, $transport) {
		echo "---- HELLO FROM InternalWampClient ----";
		$session->register('liveupdate.get',array($this,'getLiveUpdates'),array('disclose_caller'=>true));
		$session->register('liveupdate.get_unread_count',array($this,'getUnreadCount'),array('disclose_caller'=>true));
		$session->register('liveupdate.clear_unread_count',array($this,'clearUnreadCount'),array('disclose_caller'=>true));
	}
}

class WampServer extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'run:wamp';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Runs a live update server';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//Logger::set(new NullLogger());
		$router = new Router();

		$realm = new \Thruway\Realm('aaulan');

		$authorizationMgr = new AuthorizationManager($realm);
		$authMgr = new \Thruway\Authentication\AuthenticationManager();
		$router->setAuthorizationManager($authorizationMgr);
		$router->setAuthenticationManager($authMgr);


		//$wsCommandsClient = new \AAULAN\WsCommandsClient("aaulan");
		

		
		$router->addTransportProvider(new \Thruway\Transport\InternalClientTransportProvider($authMgr));
		// $router->addTransportProvider(new \Thruway\Transport\InternalClientTransportProvider($wsCommandsClient));

		$userDb = new UserDb();

		$authProvClient = new WampCraAuthProvider(["aaulan"]);
		$authProvClient->setUserDb($userDb);
		$router->addTransportProvider(new \Thruway\Transport\InternalClientTransportProvider($authProvClient));

		$websocket = new RatchetTransportProvider('127.0.0.1',9090);

		$router->addTransportProvider($websocket);

		$router->addInternalClient(new InternalWampClient());

		$router->start();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			
		);
	}

}
