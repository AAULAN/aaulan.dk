<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPizzaorderStatusFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('pizzaorders',function ($table) {
			$table->dropColumn('paid');
			$table->string('state');
			$table->datetime('paid_at');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pizzaorders',function ($table) {
			$table->dropColumn('state');
			$table->dropColumn('paid_at');
			
			$table->boolean('paid');
			
		});
	}

}
