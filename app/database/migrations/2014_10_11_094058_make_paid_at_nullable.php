<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakePaidAtNullable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pizzaorders',function($table) {
			$table->dropColumn('paid_at');
		});
		Schema::table('pizzaorders',function($table) {
			$table->datetime('paid_at')->nullable();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pizzaorders',function($table) {
			$table->dropColumn('paid_at');
		});
		Schema::table('pizzaorders',function($table) {
			$table->datetime('paid_at');
		});
	}

}
