<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tournaments',function($table) {
			$table->increments('id');
			$table->string('game');
			$table->integer('players_per_team');
			$table->boolean('require_riot_summoner_name');
			$table->integer('lan_id')->unsigned();
			$table->datetime('begins_at');
			$table->boolean('signup_open');
			$table->timestamps();
			$table->foreign('lan_id')->references('id')->on('lans');
		});
		Schema::create('team_tournament',function($table) {
			$table->increments('id');
			$table->integer('team_id')->unsigned();
			$table->integer('tournament_id')->unsigned();
			$table->timestamps();
			$table->foreign('team_id')->references('id')->on('teams');
			$table->foreign('tournament_id')->references('id')->on('tournaments');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('team_tournament');
		Schema::drop('tournaments');

	}

}
