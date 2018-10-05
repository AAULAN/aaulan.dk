<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome($showOlder = null)
	{
		if (Cache::has('tickets_sold')) {
			$sold = Cache::get('tickets_sold');
		} else {
			$sold = 0;//file_get_contents("http://coron.dk/latest.txt");
			Cache::put('tickets_sold',$sold, \Carbon\Carbon::now()->addMinutes(30));
		}
		$lan = Lan::curLan();

			$entries = Entry::with('user')->where('lan_id',$lan->id)->orderBy('id','desc')->take(5)->get();	

		$sponsors = Sponsor::orderBy(DB::raw('RAND()'))->get();

		
		$winners = $lan->winners;

		return View::make('home.index',array('entries'=>$entries,'lan'=>$lan,'winners'=>$winners,'sponsors'=>$sponsors, 'sold' => $sold));
	}

	public function postEntry() {
		if (Input::has('title') && Input::has('body')) {
			$entry = new Entry;
			$entry->title = Input::get('title');
			$entry->lan_id = Lan::curLan()->id;
			$entry->body = Input::get('body');
			Auth::user()->entries()->save($entry);
			Notification::success('Succesfully posted entry!');
		}
		return Redirect::action('HomeController@showWelcome');
	}

}
