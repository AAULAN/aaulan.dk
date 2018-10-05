<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class RefreshRiot extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'riot:refresh';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

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
		$users = User::whereNotNull('riot_summoner_name')->where('riot_status','!=','OK')->get();
		printf("%d users have summoner names but status != OK.\n",count($users));
		$delay = 1800;
		if (count($users) > 0) {
			foreach ($users as $user) {
				if (empty($user->riot_summoner_name)) continue;
				printf("Queueing %s after %d seconds.\n",$user->riot_summoner_name, $delay);
				$user->refreshRiotStats($delay);
				$delay += 30;
			}
		}

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
