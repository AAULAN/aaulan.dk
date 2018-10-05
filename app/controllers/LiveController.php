<?php

class LiveController extends BaseController {

    public function index() {

        $user = Auth::user();

        DB::table('live_update_user')->where('user_id',Auth::user()->id)->update(['seen'=>DB::raw('now()')]);

        return Response::view('live.index',['updates'=>$user->liveUpdates]);
    }

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

return Response::view('live.interface',['groups'=>$recipientsGroups,'users'=>$users]);
}

public function postMessage()
{

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

    $lu = LiveUpdate::create(['message'=>Input::get('message'),'poster_id'=>Auth::user()->id]);


    foreach ($recipients as $recipient) {
        $lu->users()->attach($recipient->id);
    }

    Queue::push('Aaulan\Live\Update',$lu->id);

    Notification::success('Your update was sent!');
    return Redirect::to('/');
}  


}