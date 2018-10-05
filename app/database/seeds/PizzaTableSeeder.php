<?php

class PizzaTableSeeder extends Seeder {
	
	public function run() {
		DB::table('pizzas')->delete();
		DB::table('pizzaextras')->delete();
		
		Pizza::create([
			'pizza_id'=>'1',
			'name'=>'Margherita',
			'price'=>40.0
		]);
		Pizza::create([
			'pizza_id'=>'2',
			'name'=>'Hawaii',
			'price'=>50.0
		]);
		Pizza::create([
			'pizza_id'=>'3',
			'name'=>'Capricciosa',
			'price'=>50.0
		]);
		
		Pizzaextra::create([
			'name'=>'Ost',
			'price'=>10.0
		]);
		Pizzaextra::create([
			'name'=>'Skinke',
			'price'=>10.0
		]);
		Pizzaextra::create([
			'name'=>'Kebab',
			'price'=>10.0
		]);
	}
	
}
