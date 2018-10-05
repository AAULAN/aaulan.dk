<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRiotFieldsToProfile extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users',function($table) {
			$table->string('riot_summoner_name')->nullable();
			$table->string('riot_tier')->nullable();
			$table->string('riot_division')->nullable();
			$table->string('riot_league_points')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('users',function($table) {
			$table->dropColumn('riot_summoner_name');
			$table->dropColumn('riot_tier');
			$table->dropColumn('riot_division');
			$table->dropColumn('riot_league_points');
		});

	}

}
