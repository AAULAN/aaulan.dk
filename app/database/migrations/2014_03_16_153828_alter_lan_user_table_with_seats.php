<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLanUserTableWithSeats extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('lan_user',function($table) {
			$table->dropForeign('lan_user_seat_id_foreign');
			$table->dropColumn('seat_id');
			$table->integer('seat_group_id')->nullable()->unsigned();
			$table->integer('seat_num')->nullable();
			
			$table->foreign('seat_group_id')->references('id')->on('seat_groups');
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
			$table->dropForeign('lan_user_seat_group_id_foreign');
			$table->dropColumn('seat_group_id');
			$table->dropColumn('seat_num');
			$table->integer('seat_id')->unsigned()->nullable();
			$table->foreign('seat_id')->references('id')->on('seats');
		});
	}

}
