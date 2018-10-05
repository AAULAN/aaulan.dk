<?php

namespace Aaulan\Live;
use User;
use LiveUpdate;
class MyClient extends \Thruway\Peer\Client
{
  private $messages;
  /**
     * @param \Thruway\AbstractSession $session
     * @param \Thruway\Transport\TransportInterface $transport
     */
    public function onSessionStart($session, $transport)
    {
      $completedCount = 0;
      printf("Sending %d messages.\n",count($this->messages));
      foreach ($this->messages as $message) {

        $session->publish($message['uri'],[$message['content']],[],["acknowledge"=>true])->then(function() use ($session, $completedCount) {
          $completedCount++;
          if ($completedCount == count($this->messages)) {
            echo "Closing\n";
            $session->close();
          }
        },function($error) use ($session, $completedCount) {
          $completedCount++;
          if ($completedCount == count($this->messages)) {
            echo "Closing\n";
            $session->close();
          }
        });
      }
    }
    public function setMessages($messages) {
      $this->messages = $messages;
    }

}

class Update {

  public function fire($job,$data) {
    $job->delete();
    $liveUpdate = LiveUpdate::findOrFail($data);

    $messages = [];
    foreach ($liveUpdate->users as $user) {
      $messages[] = ['uri'=>'liveupdate.'.$user->id,'content'=>[
        'poster'=>$liveUpdate->poster->display_sanitized,
        'message'=>$liveUpdate->message,
        'created_at'=>$liveUpdate->created_at->getTimestamp()*1000
      ]];
    }



    //\Thruway\Logging\Logger::set(new \Psr\Log\NullLogger());
    $client = new MyClient('aaulan');
    $client->setMessages($messages);
    $client->setAttemptRetry(false);
    $user = 'internal';
    $password = 'secretverygood';

    $client->setAuthId($user);
    $client->addClientAuthenticator(new \Thruway\Authentication\ClientWampCraAuthenticator($user, $password));

    $client->addTransportProvider(new \Thruway\Transport\PawlTransportProvider("ws://127.0.0.1:9090/ws"));

    $client->start();


  }


}