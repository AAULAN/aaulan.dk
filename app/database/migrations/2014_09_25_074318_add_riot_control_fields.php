<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRiotControlFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users',function($table) {
			$table->integer('riot_summoner_id')->nullable();
			$table->string('riot_status')->nullable();
			$table->string('riot_message')->nullable();
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
			$table->dropColumn('riot_summoner_id');
			$table->dropColumn('riot_status');
			$table->dropColumn('riot_message');
			
		});

	}

}
