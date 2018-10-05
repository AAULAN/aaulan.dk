<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWinnersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('winners',function($table) {
			$table->increments('id');
			$table->integer('lan_id')->unsigned();
			$table->string('game');
			$table->string('filename');
			$table->string('team_name');
			$table->integer('place');

			$table->timestamps();
			$table->foreign('lan_id')->references('id')->on('lans');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('winners');
	}

}
