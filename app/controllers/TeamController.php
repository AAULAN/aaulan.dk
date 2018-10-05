<?php

class TeamController extends BaseController
{
	public function postDisbandTeam($id) {
		$team = Team::findOrFail($id);
		foreach ($team->tournaments as $tournament) {
			if ($tournament->signup_open == 0) {
				App::abort(400,sprintf('Your team is participating in the %s tournament, and you cannot disband it.',$tournament->game));
			}
		}
		if ($team->creator->id == Auth::user()->id) {
			$team->delete();
		}
	}
	
	public function getTeam(Team $team) {
		return Response::view('team.show',array('team'=>$team));
	}
	
	
	public function postLeaveTeam($id) {
		$team = Team::findOrFail($id);
		if ($team->creator->id == Auth::user()->id) {
			App::abort(400,'You cannot leave your own teams');
		}
		foreach ($team->tournaments as $tournament) {
			if ($tournament->players_per_team > 0) {
				App::abort(400,sprintf('Your team is signed up for the %s tournament, and you can therefore not leave the team.',$tournament->game));
			}
		}
		if ($team->users->contains(Auth::user()->id)) {
			$team->users()->detach(Auth::user()->id);
		}
	}
	public function postJoinTeam($id) {
		$team = Team::findOrFail($id);
		if ($team->users->contains(Auth::user()->id)) {
			return;
		}
		foreach ($team->tournaments as $tournament) {
			if ($tournament->players_per_team > 0) {
				App::abort(400,sprintf('The team is currently signed up for the %s tournament, and you can therefore not join the team.',$tournament->game));
			}
		}
		if ($team->creator->id == Auth::user()->id) {
			$team->users()->attach(Auth::user()->id,array('accepted'=>1));
		} else {
			$team->users()->attach(Auth::user()->id,array('accepted'=>0));
			/*$data = array('name'=>$team->creator->name,'requestor'=>Auth::user()->display,'teamname'=>$team->name);
			Mail::queue('emails.request_join_team', $data, function($message) use ($team)
			{
			    $message->to($team->creator->email, $team->creator->name)->subject('AAULAN Team Request');
			});*/
		}
	}
	public function postAcceptRequest($id) {
		$tr = TeamUser::findOrFail($id);
		if ($tr->accepted == 1) return;
		if ($tr->team->creator->id == Auth::user()->id) {
			$tr->accepted = 1;
			$tr->save();
			/*$data = array('name'=>$tr->user->name,'acceptor'=>Auth::user()->display,'teamname'=>$tr->team->name);
			Mail::queue('emails.team_joined', $data, function($message) use ($tr)
			{
			    $message->to($tr->user->email, $tr->user->name)->subject('AAULAN Joined Team');
			});*/
		} else {
			App::abort(403,'You are not the creator of this team');
		}
	}
	public function postDeclineRequest($id) {
		$tr = TeamUser::findOrFail($id);
		if ($tr->accepted == 1) return;
		if ($tr->team->creator->id == Auth::user()->id) {
			
			$tr->delete();
		} else {
			App::abort(403,'You are not the creator of this team');
		}
	}
	public function postTeam() {
		if (Input::has('name')) {
			$team = new Team(Input::only('name'));
			$team->creator()->associate(Auth::user());
			$team->save();
			$team->users()->attach(Auth::user()->id,array('accepted'=>1));
			Notification::success('Team has been created!');
			return Redirect::action('UserController@getProfile');
		} else {
			Notification::danger('Team was not created!');
			return Redirect::action('UserController@getProfile');
		}
	}
}
