<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePizzaTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pizzas',function($table) {
			$table->increments('id')->unsigned();
			$table->string('pizza_id',5);
			$table->string('name');
			$table->decimal('price',5,2);
			$table->timestamps();
		});
		Schema::create('pizzaextras',function($table) {
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->decimal('price',5,2);
			$table->timestamps();
		});
		Schema::create('pizzaorder_rounds',function($table) {
			$table->increments('id')->unsigned();
			$table->integer('lan_id')->unsigned();
			$table->datetime('opens');
			$table->datetime('closes');
			$table->datetime('delivery');
			$table->string('name');
			$table->timestamps();
			$table->foreign('lan_id')->references('id')->on('lans');
		});
		Schema::create('pizzaorders',function($table) {
			$table->increments('id')->unsigned();
			$table->integer('round_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->decimal('price',6,2);
			$table->boolean('paid');
			$table->timestamps();
			$table->softDeletes();
			
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('round_id')->references('id')->on('pizzaorder_rounds');
		});
		Schema::create('pizzaorder_pizza',function($table) {
			$table->increments('id')->unsigned();
			$table->integer('pizzaorder_id')->unsigned();
			$table->integer('pizza_id')->unsigned();
			$table->integer('quantity')->unsigned();
			$table->string('comment')->nullable();
			$table->string('extras')->nullable();
			$table->decimal('extra_price',5,2)->nullable();
			
			$table->foreign('pizza_id')->references('id')->on('pizzas');
			$table->foreign('pizzaorder_id')->references('id')->on('pizzaorders');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('pizzaorder_pizza',function($table) {
			$table->dropForeign('pizzaorder_pizza_pizza_id_foreign');
			$table->dropForeign('pizzaorder_pizza_pizzaorder_id_foreign');
		});
		Schema::table('pizzaorders',function($table) {
			$table->dropForeign('pizzaorders_round_id_foreign');
		});
		Schema::table('pizzaorder_rounds',function($table) {
			$table->dropForeign('pizzaorder_rounds_lan_id_foreign');
		});
		Schema::drop('pizzas');
		Schema::drop('pizzaextras');
		Schema::drop('pizzaorders');
		Schema::drop('pizzaorder_rounds');
		Schema::drop('pizzaorder_pizza');
	}

}
