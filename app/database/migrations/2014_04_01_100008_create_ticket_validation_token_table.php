<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketValidationTokenTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_validation_tokens',function($table) {
			$table->integer('user_id')->unsigned();
			$table->string('token')->unique();
			$table->string('email');
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
		Schema::table('ticket_validation_tokens',function($table) {
			$table->dropForeign('ticket_validation_tokens_user_id_foreign');
		});
		Schema::drop('ticket_validation_tokens');
	}

}
