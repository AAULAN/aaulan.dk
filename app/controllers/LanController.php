<?php

use Carbon\Carbon;

class LanController extends BaseController {
	
	public function getLans() {
		$lans = Lan::with('users')->get();
		$lanDropdown = [];
		foreach ($lans as $l) {
			$lanDropdown[$l->id] = sprintf('#%d - %s',$l->id,$l->name);
		} 
		return View::make('admin.lan.list',['lans'=>$lans,'lan_dropdown'=>$lanDropdown,'message'=>Session::get('message')]);
	}
	public function postLans() {
		$data = Input::only('name','description','price_member','price_nonmember','ticket_link','seats');
		$data['opens'] = Input::get('opens_date')." ".Input::get('opens_time');
		$data['closes'] = Input::get('closes_date')." ".Input::get('closes_time');
		
		$validator = Validator::make($data,[
			'name'=>'required',
			'description'=>'required',
			'opens'=>'required|date',
			'closes'=>'required|date',
			'price_member'=>'required',
			'price_nonmember'=>'required',
			'ticket_link'=>'url',
			'seats'=>'required|numeric'
		]);
		if ($validator->fails()) {
			return Redirect::action('LanController@getCreateForm')->withInput()->withErrors($validator);
		} else {
			$lan = Lan::create($data);
			return Redirect::action('LanController@getLans')->with('message','Lan #'.$lan->id.' has been created!');
		}
	}
	public function getCreateForm() {
		
		return View::make('admin.lan.createedit',['edit'=>false]);
	}
	public function postLan($id) {
		$lan = Lan::findOrFail($id);
		$data = Input::only('name','description','price_member','price_nonmember','ticket_link','seats');
		$data['opens'] = Input::get('opens_date')." ".Input::get('opens_time');
                $data['closes'] = Input::get('closes_date')." ".Input::get('closes_time');
		
		$validator = Validator::make($data,[
			'name'=>'required',
			'description'=>'required',
			'opens'=>'required|date',
			'closes'=>'required|date',
			'price_member'=>'required',
			'price_nonmember'=>'required',
			'ticket_link'=>'url',
			'seats'=>'required|numeric'
		]);
		if ($validator->fails()) {
			return Redirect::action('LanController@getEditForm',$id)->withInput()->withErrors($validator);
		} else {
			$lan->update($data);
			return Redirect::action('LanController@getLans')->with('message','Lan #'.$lan->id.' has been updated!');
		}
	}
	public function getEditForm($id) {
		$lan = Lan::findOrFail($id);
		$data = $lan->toArray();
		$open = new Carbon($data['opens']);
		$close = new Carbon($data['closes']);
		$data['opens_date'] = $open->toDateString();
		$data['opens_time'] = $open->toTimeString();
		$data['closes_date'] = $close->toDateString();
		$data['closes_time'] = $close->toTimeString();
		
		return View::make('admin.lan.createedit',['edit'=>true,'lan'=>$data]);
	}
	public function postSetActive() {
		$id = Input::get('id');
		$lan = Lan::findOrFail($id);
		if ($lan) {
			DB::table('lans')->update(['active'=>null]);
			$lan->active = 1;
			$lan->save();
		}
		return Redirect::action('LanController@getLans')->with('message','Lan #'.$lan->id.' has been set as the active lan.');
	}
}
