<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateValidationsTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('validation_tokens',function($table) {
			$table->integer('user_id')->unsigned();
			$table->string('token')->unique();
			$table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('validation_tokens',function($table) {
			$table->dropForeign('validation_tokens_user_id_foreign');
		});
		Schema::drop('validation_tokens');
	}

}
