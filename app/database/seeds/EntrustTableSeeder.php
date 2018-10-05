<?php

class EntrustTableSeeder extends Seeder {
	
	public function run() {
		DB::table('permission_role')->delete();
		DB::table('assigned_roles')->delete();	
		DB::table('roles')->delete();
		DB::table('permissions')->delete();
		
		
		$web = Role::create(['name'=>'Webmaster']);
		$admin = Role::create(['name'=>'Administrator']);
		$crew = Role::create(['name'=>'Crew']);
		
		$view_log = Permission::create(['name'=>'view_log','display_name'=>'Can read Logs']);
		
		$lans = Permission::create(['name'=>'lans','display_name'=>'Can show LANs']);
		$lans_edit = Permission::create(['name'=>'lans_edit','display_name'=>'Can administer LANs']);
		$users = Permission::create(['name'=>'users','display_name'=>'Can show Users']);
		$users_edit = Permission::create(['name'=>'users_edit','display_name'=>'Can administer Users']);
		$seats = Permission::create(['name'=>'seats','display_name'=>'Can show Seats']);
		$seats_edit = Permission::create(['name'=>'seats_edit','display_name'=>'Can administer Seats']);
		$tournaments = Permission::create(['name'=>'tournaments','display_name'=>'Can show Tournaments']);
		$tournaments_edit = Permission::create(['name'=>'tournaments_edit','display_name'=>'Can administer Tournaments']);
		
		DB::table('permission_role')->insert([
			// Webmaster permissions
			['role_id'=>$web->id,'permission_id'=>$view_log->id],
			['role_id'=>$web->id,'permission_id'=>$lans->id],
			['role_id'=>$web->id,'permission_id'=>$lans_edit->id],
			['role_id'=>$web->id,'permission_id'=>$users->id],
			['role_id'=>$web->id,'permission_id'=>$users_edit->id],
			['role_id'=>$web->id,'permission_id'=>$seats->id],
			['role_id'=>$web->id,'permission_id'=>$seats_edit->id],
			['role_id'=>$web->id,'permission_id'=>$tournaments->id],
			['role_id'=>$web->id,'permission_id'=>$tournaments_edit->id],
			
			// Admin permissions
			['role_id'=>$admin->id,'permission_id'=>$lans->id],
			['role_id'=>$admin->id,'permission_id'=>$lans_edit->id],
			['role_id'=>$admin->id,'permission_id'=>$users->id],
			['role_id'=>$admin->id,'permission_id'=>$users_edit->id],
			['role_id'=>$admin->id,'permission_id'=>$tournaments->id],
			['role_id'=>$admin->id,'permission_id'=>$tournaments_edit->id],
			
			// Crew permissions
			['role_id'=>$crew->id,'permission_id'=>$lans->id],
			['role_id'=>$crew->id,'permission_id'=>$users->id],
			['role_id'=>$crew->id,'permission_id'=>$tournaments->id],
			
			
		]);
		DB::table('assigned_roles')->insert(array('user_id'=>1,'role_id'=>1));
	}
	
}
