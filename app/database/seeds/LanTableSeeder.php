<?php

class LanTableSeeder extends Seeder {
	
	public function run() {
		DB::table('lans')->delete();

		
		Lan::create([
			'name'=>'AAULAN Spring \'14',
			'description'=>'',
			'opens'=>'2014-04-11 16:00:00',
			'closes'=>'2014-04-13 12:00:00',
			'price_member'=>50,
			'price_nonmember'=>60,
			'ticket_link'=>'http://webshop.studentersamfundet.aau.dk/',
			'active'=>1,
			'seats'=>150
			
		]);
	}
	
}
