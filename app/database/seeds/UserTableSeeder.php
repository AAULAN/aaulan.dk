<?php

class UserTableSeeder extends Seeder {
	
	public function run() {
		DB::table('users')->delete();
		
		User::create(['email'=>'dsaren12@student.aau.dk','name'=>'Daniel Amkær Sørensen','password'=>Hash::make('daniel'),'display_name'=>'amkaer','phone'=>'53302495','ida'=>2021497]);
	}
	
}
