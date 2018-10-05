<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLiveTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('live_updates',function($table) {
			$table->increments('id');
			$table->text('message');
			$table->integer('poster_id')->unsigned();
			$table->timestamps();
			$table->foreign('poster_id')->references('id')->on('users')->onDelete('cascade');
		});

		Schema::create('live_update_user',function($table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('live_update_id')->unsigned();
			$table->dateTime('seen')->nullable();

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('live_update_id')->references('id')->on('live_updates')->onDelete('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('live_update_user');
		Schema::drop('live_updates');
	}

}
