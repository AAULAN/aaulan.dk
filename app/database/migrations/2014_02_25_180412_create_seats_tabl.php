<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeatsTabl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('seats',function($table) {
			$table->increments('id')->unsigned();
			$table->softDeletes();
			$table->timestamps();
		});
		Schema::table('lan_user',function($table) {
			$table->foreign('seat_id')->references('id')->on('seats');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('lan_user',function($table) {
			$table->dropForeign('lan_user_seat_id_foreign');
		});
		Schema::drop('seats');
	}

}
