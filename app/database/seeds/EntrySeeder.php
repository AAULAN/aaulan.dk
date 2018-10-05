<?php

class EntrySeeder extends Seeder {
	
	public function run() {
		DB::table('entries')->delete();
		$u = User::first();
		$e = new Entry(array(
			'title'=>'Finally!',
			'body'=>"Our new homepage is ready for use - as you can see, the homepage is completely redesigned, as well as the logo and other stuff!\nWe hope you're ready for the forthcoming LAN event (sold out!). Be nice!\n\nShould you have any questions regarding the homepage/event itself, send us an e-mail on crew(snalbela)aaulan.dk",
		));
		$u->entries()->save($e);
	}
	
}
