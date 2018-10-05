<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanIdToEntries extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('entries',function($table) {
			$table->integer('lan_id')->unsigned()->nullable();
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
		Schema::table('entries',function($table) {
			$table->dropColumn('lan_id');
		});
	}

}
