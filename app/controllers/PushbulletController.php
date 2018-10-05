<?php


class PushbulletController extends Controller {

  public function getAuthComplete() {
    if (Input::has('error')) {
      if (Input::get('error') == 'access_denied') {
        Notification::danger('You denied access on PushBullet\'s website.');
      } else {
        Notification::danger('An error occurred while authorizing AAULAN at PushBullet.');
      }
      return Redirect::action('UserController@getProfile');
    }
    $user = Auth::user();
    $code = Input::get('code');
    $postdata = [
      'grant_type'=>'authorization_code',
      'client_id'=>'weEdudNBBEuW7b7WLnwTVKoFjve3L4PX',
      'client_secret'=>'9uZaxno0b15VRXg0tdR3zY7b2TYyxJp2',
      'code'=>$code
    ];

    $ch = curl_init('https://api.pushbullet.com/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));

    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);

    $resp = curl_exec($ch);

    if ($resp === false) {
      Notification::danger('PushBullet authorization failed.');
      return Redirect::action('UserController@getProfile');
    } else {
      $data = json_decode($resp,true);
      if ($data) {
        $user->pushbullet_access_token = $data['access_token'];
        $user->save();
        Notification::success('Successfully associated your Pushbullet account with your AAULAN account.');
        return Redirect::action('UserController@getProfile');
      } else {
        Notification::danger('Could not decode information received from PushBullet.');
        return Redirect::action('UserController@getProfile');
      }
    }
  }

  public function getSendTest() {

    $user = Auth::user();

    $ch = curl_init('https://api.pushbullet.com/v2/pushes');

    $data = array("type"=>'note','title'=>'AAULAN','body'=>'The AAULAN Crew greets you!');
    $data_string = json_encode($data);                                                                                   
 
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string),
        'Authorization: Bearer '.$user->pushbullet_access_token)                                                                       
    );

    curl_exec($ch);
    Notification::success('Tried to send PushBullet notification.');
    return Redirect::action('UserController@getProfile');

  }

  public function getAuthStatus() {
    $user = Auth::user();
    if (!$user->pushbullet_access_token) {
      return '<p>Your account is not linked with PushBullet.</p>';
    } else {
      $ch = curl_init('https://api.pushbullet.com/v2/users/me');
                                                              
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
      curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
          'Authorization: Bearer '.$user->pushbullet_access_token)                                                                       
      );

      $resp = curl_exec($ch);
      if (!$resp) {
        return '<p>No data returned from PushBullet.</p>';
      } else {
        $data = json_decode($resp,true);
        if (!$data) {
          return '<p>Unable to parse PushBullet response.</p>';
        } else {
          return '<p>Linked with '.$data['name'].' on PushBullet.</p>';
        }
      }
    }
  }


}