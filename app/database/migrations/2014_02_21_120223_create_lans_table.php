<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLansTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('lans',function($table) {
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->text('description')->nullable();
			$table->dateTime('opens')->nullable();
			$table->dateTime('closes')->nullable();
			$table->decimal('price_member',5,2);
			$table->decimal('price_nonmember',5,2);
			$table->string('ticket_link')->nullable();
			$table->boolean('active')->unique()->nullable();
			$table->integer('seats');
			
			$table->timestamps();
		});
		
		Schema::create('lan_user',function($table) {
			$table->increments('id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('lan_id')->unsigned();
			$table->string('ticket_id');
			$table->integer('seat_id')->unsigned()->nullable();
			$table->timestamps();
			$table->foreign('user_id')->references('id')->on('users');
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
		Schema::table('lan_user',function($table) {
			$table->dropForeign('lan_user_user_id_foreign');
			$table->dropForeign('lan_user_lan_id_foreign');
		});
		Schema::drop('lans');
		Schema::drop('lan_user');
	}

}
