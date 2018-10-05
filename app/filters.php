<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	$user = Auth::user();
	if ($user) {
		$user->last_activity = \Carbon\Carbon::now();
		$user->save();
	}
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest(URL::action('UserController@getLogin'));
});
	
Route::filter('has_admission', function() {
	if (Auth::guest()) return Redirect::guest(URL::action('UserController@getLogin'));
	if (!Auth::user()->hasAdmission()) return Redirect::guest(URL::action('TicketController@getSignUp'));
});
	
Route::filter('validated', function() {
	if (Auth::guest()) return Redirect::guest(URL::action('UserController@getLogin'));
	if (Auth::user()->validated == 0) return Redirect::to(URL::action('UserController@getProfile'));
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

Route::filter('role',function($route,$request,$value) {
	
	if (!Entrust::hasRole($value)) {
		App::abort(404);
	}
});

Route::filter('permission',function($route,$request,$value) {
	if (!Entrust::can($value)) {
		App::abort(404);
	}
});
/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

Entrust::routeNeedsRole('admin/*',array('Webmaster','Administrator','Crew'),null,false);
