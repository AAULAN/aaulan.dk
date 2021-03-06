<?php

class GamesController extends BaseController {
	
	public function getTournaments() {
		
		
		$tournaments = Tournament::all();
		
		return Response::view('tournaments.index',array('tournaments'=>$tournaments));
		
	}
	
	public function getTournament($id) {
		if (!is_numeric($id)) {
			return Redirect::action('GamesController@getTournaments');
		}
		$t = Tournament::findOrFail($id);
		if ($t->players_per_team == 1) {
			$joined = false;
			foreach ($t->users as $u) {
				if ($u->id == Auth::id()) {
					$joined = true; break;
				}
			}
			return Response::view('tournaments.show',array('tournament'=>$t,'joined'=>$joined));
		} else {
			$teamson = Team::with(array('users'=>function($query) {
				$query->where('accepted','=',1);
			}))->get();
			$yourteams = $teamson->filter(function($model) {
				return ($model->user_id == Auth::user()->id);
			});
			$user_id = Auth::user()->id;
			$teamson = $teamson->filter(function($model) use ($user_id) {
				foreach ($model->users as $user) {
					if ($user->id == $user_id) return true;
				}
				return false;
			});
			
			$teams = array();
			foreach ($yourteams as $team) {
				$teams[$team->id] = $team->name;
			}	
			$joined = false;
			foreach ($t->teams as $t1) {
				foreach ($teamson as $t2) {
					if ($t1->id == $t2->id) {
						$joined = true; break;
					}
				}
			}
			return Response::view('tournaments.show',array('tournament'=>$t,'yourteams'=>$teams,'joined'=>$joined));
		}
		
		
		
		
	}

	public function postJoinTournament($id) {
		if (!is_numeric($id)) {
			return Redirect::action('GamesController@getTournaments');
		}
		$tournament = Tournament::findOrFail($id);
		if (!$tournament->signup_open) {
			Notification::danger("Signups are closed for this tournament.");
			return Redirect::action('GamesController@getTournament',$id);	
		}

		if ($tournament->team_limit > 0 && (count($tournament->teams) + count($tournament->users)) >= $tournament->team_limit)
		{
			Notification::danger('Unable to sign up as the team limit for this tournament has been reached.');
			return Redirect::action('GamesController@getTournament',$id);	
		}
	
		if ($tournament->players_per_team == 1) {
			foreach ($tournament->users as $u) {
				if ($u->id == Auth::user()->id) {
					Notification::danger('You are already signed up.');
					return Redirect::action('GamesController@getTournament',$id);	
				}
			}
			$result = $tournament->users()->save(Auth::user());
			Slack::to("#turnering_servere")->send(sprintf("%s signed up for %s.",Auth::user()->display_sanitized,$tournament->game));
		} else {
			$tournament = Tournament::with('teams.users')->findOrFail($id);
			if (!Input::has('team')) {
				return Redirect::action('GamesController@getTournaments');		
			}
			$team = Team::with('users')->findOrFail(Input::get('team'));
			if ($team->creator->id != Auth::user()->id) {
				App::abort(403,'You are not the creator of this team');
			}

			$team_players = [];
			foreach ($team->users as $u) {
				$team_players[] = $u->id;
			}
			foreach ($tournament->teams as $t1) {
				foreach ($t1->users as $u1) {
					if (in_array($u1->id, $team_players)) {
						Notification::danger('A player in your team is already signed up.');
						return Redirect::action('GamesController@getTournament',$id);	
					}
				}
			}
			if ($tournament->players_per_team > 0 && count($team->users) != $tournament->players_per_team) {
				Notification::danger(sprintf('Your team must have %d players.',$tournament->players_per_team));
				return Redirect::action('GamesController@getTournament',$id);	
			}
			if ($tournament->require_riot_summoner_name) {
				$usersMissing = [];
				foreach ($team->users as $user) {
					if (!$user->riot_summoner_name || $user->riot_status != 'OK') {
						$usersMissing[] = $user->getDisplayAttribute();
					}
				}
				if (count($usersMissing) > 0) {
					Notification::danger(sprintf('To join this tournament, every player must enter their Summoner Name in their profile and must have their tier and division listed in their profile. The following users are missing their summoner name: %s',join(', ',$usersMissing)));
					return Redirect::action('GamesController@getTournament',$id);	
				}
			}
			$result = $tournament->teams()->save($team);
			Slack::to("#turnering_servere")->send(sprintf("%s signed up for %s.",$team->name,$tournament->game));
		}

		

		

		
		if ($result) {
			Notification::success("Joined tournament!");
			return Redirect::action('GamesController@getTournament',$id);	
		} else {
			Notification::danger('Failed to join tournament');
			return Redirect::action('GamesController@getTournament',$id);
		}
		
	}

	public function getLeaveTournament($id,$teamid = null) {
		$tournament = Tournament::findOrFail($id);
		if (!$tournament->signup_open) {
			Notification::danger("Signups are closed for this tournament.");
			return Redirect::action('GamesController@getTournament',$id);	
		}
		if ($tournament->players_per_team == 1) {
			$tournament->users()->detach(Auth::user());
		} else {
			$team = Team::findOrFail($teamid);	
			if ($team->creator->id != Auth::id()) {
				App::abort(403,'You are not the creator of this team');
			}
			$tournament->teams()->detach($team);
		}
		return Redirect::action('GamesController@getTournament',$id);	
	}
		
	public function getTeamList($id) {
		$tournament = Tournament::findOrFail($id);

		$teams = array();
		$resp = "";

		foreach ($tournament->teams as $team) {

			$entry = array();
			$entry['team_name'] = $team->name;

			$score = 0;

			if ($tournament->require_riot_summoner_name) {
				foreach ($team->users as $user) {
					$division = 0;
					$tier = 0;
					switch ($user->riot_tier)
					{
						case "CHALLENGER":
							$division = 35;
							break;
						case "MASTER":
							$division = 30;
							break;
						case "DIAMOND":
							$division = 20;
							break;
						case "PLATINUM":
							$division = 15;
							break;
						case "GOLD":
							$division = 10;
							break;
						case "SILVER":
							$division = 5;
							break;
						case "BRONZE":
							$division = 0;
							break;
					}
					switch ($user->riot_division) {
						case "V":
							$tier = 1;
							break;
						case "IV":
							$tier = 2;
							break;
						case "III":
							$tier = 3;
							break;
						case "II":
							$tier = 4;
							break;
						case "I":
							$tier = 5;
							break;
					}
					$score += $division+$tier;

				}
			}
			$score /= $tournament->players_per_team;
			$entry['team_score'] = $score;
			$resp .= sprintf("%s\t%s\n",$entry['team_name'],$entry['team_score']);
			$teams[] = $entry;

		}
		$teams = array_reverse(array_sort($teams, function($value) {
			return $value["team_score"];
		}));
		$resp = "";
		foreach ($teams as $t) {
			$resp .= sprintf("%s\t%s\n",$t['team_name'],$t['team_score']);
		}


		$response = Response::make($resp, 200);

		$response->header('Content-Type', 'text/plain');

		return $response;
		

	}

	
}
