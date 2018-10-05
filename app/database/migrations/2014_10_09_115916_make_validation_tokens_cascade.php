<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeValidationTokensCascade extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('validation_tokens',function($table) {
			$table->dropForeign('validation_tokens_user_id_foreign');
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
			$table->foreign('user_id')->references('id')->on('users');
		});
	}

}
