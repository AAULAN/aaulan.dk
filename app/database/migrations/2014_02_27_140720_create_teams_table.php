<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('teams',function($table) {
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->integer('user_id')->unsigned()->nullable(); // creator
			$table->timestamps();
		});
		
		Schema::create('team_user',function($table) {
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('team_id')->unsigned();
			$table->boolean('accepted')->nullable();
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('team_id')->references('id')->on('teams');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
		Schema::table('team_user',function($table) {
			$table->dropForeign('team_user_user_id_foreign');
			$table->dropForeign('team_user_team_id_foreign');
		});
		Schema::drop('teams');
		Schema::drop('team_user');
	}

}
