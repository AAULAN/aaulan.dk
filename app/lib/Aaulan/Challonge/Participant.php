<?php

namespace Aaulan\Challonge;

class Participant extends Api {
	
	protected $url = "tournaments/:tournament/participants";
	
	protected $tournament = null;
	
	public function setTournament($tournament) {
		$this->tournament = $tournament;
	}
	
	
	
}
