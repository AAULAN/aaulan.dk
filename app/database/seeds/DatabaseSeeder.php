<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
		$this->call('EntrustTableSeeder');
		$this->call('LanTableSeeder');
		$this->call('PizzaTableSeeder');
		$this->call('EntrySeeder');
	}

}