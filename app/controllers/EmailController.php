<?php

class EmailController extends Controller
{

  public function getInterface()
  {

    $recipientsGroups = array();

    // All validated users
    $group = array();
    $group['name']='All validated users';
    $group['users']=[];
    $users = User::where('validated',1)->get();
    foreach ($users as $user) {
      array_push($group['users'],$user->id);
    }
    array_push($recipientsGroups,$group);


    // All users with tickets
    $group = array();
    $group['name']='All users with tickets';
    $group['users']=[];
    $users = User::where('validated',1)->get()->filter(function($user) {
      return $user->hasAdmission();
    });
    foreach ($users as $user) {
      array_push($group['users'],$user->id);
    }
    array_push($recipientsGroups,$group);

    // All users with tickets
    $group = array();
    $group['name']='All users without tickets';
    $group['users']=[];
    $users = User::where('validated',1)->get()->filter(function($user) {
      return !$user->hasAdmission();
    });
    foreach ($users as $user) {
      array_push($group['users'],$user->id);
    }
    array_push($recipientsGroups,$group);

    // All users with seats
    $group = array();
    $group['name']='All users with seat';
    $group['users']=[];
    $users = User::where('validated',1)->get()->filter(function($user) {
      return $user->hasAdmission() && $user->hasSeat();
    });
    foreach ($users as $user) {
      array_push($group['users'],$user->id);
    }
    array_push($recipientsGroups,$group);

    // All users with seats
    $group = array();
    $group['name']='All users without seat';
    $group['users']=[];
    $users = User::where('validated',1)->get()->filter(function($user) {
      return $user->hasAdmission() && !$user->hasSeat();
    });
    foreach ($users as $user) {
      array_push($group['users'],$user->id);
    }
    array_push($recipientsGroups,$group);

    // All users with unpaid pizza order
    $rounds = PizzaorderRound::where('lan_id',Lan::curLan()->id)->get();
    foreach ($rounds as $round) {


      $group = array();
      $group['name']='Users with unpaid pizza order in '.$round->name;
      $group['users']=[];
      $users = $round->orders->filter(function($order) {
        return $order->paid == 0;
      });
      foreach ($users as $user) {
        array_push($group['users'],$user->user_id);
      }
      array_push($recipientsGroups,$group);

    }

    // All users with unpaid pizza order
    $rounds = PizzaorderRound::where('lan_id',Lan::curLan()->id)->get();
    foreach ($rounds as $round) {


      $group = array();
      $group['name']='Users with paid pizza order in '.$round->name;
      $group['users']=[];
      $users = $round->orders->filter(function($order) {
        return $order->paid == 1;
      });
      foreach ($users as $user) {
        array_push($group['users'],$user->user_id);
      }
      array_push($recipientsGroups,$group);

    }

    // Users signed up for tournament
    $tournaments = Tournament::where('lan_id',Lan::curLan()->id)->get();
    foreach ($tournaments as $tournament) {
      $group = array();
      $group['name']='Users signed up for '.$tournament->game;
      $group['users']=[];

      foreach ($tournament->users as $user) {
        array_push($group['users'],$user->id);
      }
      foreach ($tournament->teams as $team) {
        foreach ($team->users as $user) {
          array_push($group['users'],$user->id);
        }
      }
      array_push($recipientsGroups,$group);

    }

    // Users on team
    $teams = Team::all();
    foreach ($teams as $team) {
      $group = array();
      $group['name']='Users on team '.$team->name;
      $group['users']=[];

      foreach ($team->users as $user) {
        array_push($group['users'],$user->id);
      }
      array_push($recipientsGroups,$group);
    }

    $users = User::orderBy('name')->get();
   
    return Response::view('admin.email.interface',['groups'=>$recipientsGroups,'users'=>$users]);
  }

  public function postPreview() {
    
    $recipients = array();
    if (Input::has('groups')) {

      $groups = Input::get('groups');
      foreach ($groups as $group) {
        $users = explode(',',$group);
        foreach ($users as $id) {
          $user = User::findOrFail($id);
          $recipients[] = $user;
        }
      }

    }
    if (Input::has('users')) {
      $users = Input::get('users');
      foreach ($users as $id) {
        $user = User::findOrFail($id);
        $recipients[] = $user;
      }
    }

    $recipients = array_unique($recipients);
    Session::put('content',Input::get('message'));
    Session::put('recipients',$recipients);
    $filename = uniqid(time()).'.html';
    $content = (string)View::make('emails.admin_email',['name'=>Auth::user()->name,'content'=>Input::get('message')]);
    File::put(public_path().'/gen/'.$filename,$content);
    
    return Response::view('admin.email.preview',['filename'=>$filename,'recipients'=>$recipients]);



  }

  public function postSend() {
    if (!Session::has('recipients') || !Session::has('content')) {
      Notification::warning('No data supplied');
      return Redirect::action('EmailController@getInterface');
    }
    $recipients = Session::get('recipients');
    $content = Session::get('content');
    $queued = 0;
    $senderemail = Auth::user()->email;
    $sendername = Auth::user()->name;
    foreach ($recipients as $user) {
      $username = $user->name;
      $useremail = $user->email;
      
      Mail::queue('emails.admin_email',['name'=>$user->name,'content'=>$content],function($msg) use ($useremail,$username,$senderemail,$sendername) {
        $msg->to($useremail,$username);
        $msg->subject('Message from the Crew');
        $msg->returnPath($senderemail,$sendername);
        $msg->sender('no-reply@aaulan.dk','AAULAN');
      });
      $queued++;
    }

    Mail::queue('emails.systemreport',['msg'=>['Finished sending mail with content',$content,'Sent to',array_pluck($recipients,'email'),'Number queued: '.$queued]],function($msg) use ($senderemail,$sendername) {
      $msg->subject('Finished sending e-mail');
      $msg->to($senderemail,$sendername);
    });

    Session::forget('recipients');
    Session::forget('content');

    return Redirect::action('EmailController@getSuccess')->with('queued',$queued);
    

  }

  public function getSuccess() {
    if (Session::has('queued')) {
      $queued = Session::get('queued');
    } else {
      $queued = 0;
    }

    return Response::view('admin.email.success',['queued'=>$queued]);
  }

}