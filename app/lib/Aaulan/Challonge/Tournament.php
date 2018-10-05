<?php

namespace Aaulan\Challonge;

class Tournament extends Api {
	
	protected $url = "tournaments/:tournament";
	
	protected $tournament = null;
	
	public function setTournament($tournament) {
		$this->tournament = $tournament;
	}
	
	public function startTournament() {
		$this->prepare('post',null,'tournaments/:tournament/start');
		$this->execute();
		
		return $this->result;
	}
	public function resetTournament() {
		$this->prepare('post',null,'tournaments/:tournament/reset');
		$this->execute();
		
		return $this->result;
	}
	public function finalizeTournament() {
		$this->prepare('post',null,'tournaments/:tournament/finalize');
		$this->execute();
		
		return $this->result;
	}
	
}
