<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatingGroupsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seat_groups',function($table) {
			$table->increments('id');
			$table->integer('xpos');
			$table->integer('ypos');
			$table->string('orientation',1);
			$table->string('size',20);
			$table->integer('xcount');
			$table->integer('ycount');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('seat_groups');
	}

}
