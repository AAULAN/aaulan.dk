<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Aaulan\Seats\EloquentSeatRepository as Seat;

class UpdateSeats extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'seats:update';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update actual seat numbers.';

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
    		$repo = new Seat;

		$seats = $repo->getSeats();

		foreach ($seats as $seat) {
			if (!$seat->user) continue;
			$lu = LanUser::where('seat_group_id', $seat->group)->where('seat_num', $seat->seatnum)->where('lan_id', Lan::curLan()->id)->first();
			if (!$lu) {
				printf("lu not found for %s\n",$seat->user->name);
				continue;
			}
			$lu->gseatnum = $seat->gseatnum;
			$lu->save();
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
