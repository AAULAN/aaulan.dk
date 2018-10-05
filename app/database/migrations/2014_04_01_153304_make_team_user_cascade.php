<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeTeamUserCascade extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('team_user',function($table) {
			$table->dropForeign('team_user_team_id_foreign');
			$table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
			$table->dropForeign('team_user_user_id_foreign');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('team_user',function($table) {
			$table->dropForeign('team_user_team_id_foreign');
			$table->foreign('team_id')->references('id')->on('teams');
			$table->dropForeign('team_user_user_id_foreign');
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

}
