<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsToUsers extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users',function($table) {
			$table->string('display_name',40)->unique()->nullable();
			$table->string('phone',12)->nullable();
			$table->integer('ida')->nullable()->unique();
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
		Schema::table('users',function($table) {
			$table->dropColumn('display_name');
			$table->dropColumn('phone');
			$table->dropColumn('ida');
		});
	}

}
