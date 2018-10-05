<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
use Aaulan\Challonge\Tournament;
use Aaulan\Seats\SeatRepository as Seat;
Route::get('/test','DevelopmentController@getTest');
// Route admin/* is protected by Entrust in app/filters.php
Route::group(['prefix'=>'admin-old'],function() {
	// Lans table
	Route::group(['before'=>'permission:lans','prefix'=>'lans'],function() {
		
		Route::get('{id}/edit',['before'=>'permission:lans_edit','uses'=>'LanController@getEditForm']);
		Route::post('{id}',['before'=>'permission:lans_edit','uses'=>'LanController@postLan']);
		Route::get('',['uses'=>'LanController@getLans']);
		Route::post('',['before'=>'permission:lans_edit','uses'=>'LanController@postLans']);
		Route::post('set-active',['before'=>'permission:lans_edit','uses'=>'LanController@postSetActive']);
		Route::get('create',['before'=>'permission:lans_edit','uses'=>'LanController@getCreateForm']);
		
	});
	
	Route::get('/pizza/export/{id}',['before'=>'permission:pizzas','uses'=>'PizzaController@getExport']);
	Route::post('/pizza/pdf/{id}',['before'=>'permission:pizzas','uses'=>'PizzaController@postPdf']);
	
	Route::group(['before'=>'permission:users','prefix'=>'users'],function() {
		
		Route::get('','UserController@getUsers');	
		Route::get('contracts','UserController@adminGetContracts');
		
	});
	
	Route::group(['before'=>'permission:seats_edit','prefix'=>'seating'],function() {
		Route::get('',['uses'=>'SeatController@adminGetSeating']);
		Route::get('groups.json',['uses'=>'SeatController@getGroupsJson']);
		Route::post('groups.json',['uses'=>'SeatController@postGroupsJson']);		
	});
	
	Route::get('email',['uses'=>'EmailController@getInterface','before'=>'permission:email']);
	Route::post('email',['uses'=>'EmailController@postPreview','before'=>'permission:email']);
	Route::post('email/send',['uses'=>'EmailController@postSend','before'=>'permission:email']);
	Route::get('email/success',['uses'=>'EmailController@getSuccess','before'=>'permission:email']);
	
	Route::get('/live','LiveController@getInterface');
	Route::post('/live','LiveController@postMessage');
	
});
Route::bind('user',function($value) {
	if (is_numeric($value)) {
		$user = User::find($value);
	} else {
		$user = User::where('slug','=',$value)->first();
	}
	if (!$user) {
		App::abort(404);
	}
	return $user;
});
Route::bind('page',function($value) {

	$page = Page::where('slug','=',$value)->first();

	if (!$page) {
		App::abort(404);
	}
	return $page;
});
Route::bind('team',function($value) {

	$team = Team::find($value);

	if (!$team) {
		App::abort(404);
	}
	return $team;
});
Route::get('/slideshow/nextup','SlideshowController@getNextUp');
Route::get('/slideshow/slide/{curSlide}','SlideshowController@getNextSlide');
Route::get('/slideshow','SlideshowController@getIndex');

Route::get('/schedule','ScheduleController@getIndex');
Route::get('/schedule/gantt','ScheduleController@getGantt');

Route::get('/crew','CrewController@index');

Route::get('/live',['before'=>'validated','uses'=>'LiveController@index']);

Route::get('/user/{user}',['before'=>'validated','uses'=>'UserController@getShowProfile']);
Route::get('/info/{page}',['uses'=>'PageController@getPage']);
Route::get('/info','PageController@getIndex');

Route::get('/ticket/activate',['before'=>'validated','uses'=>'TicketController@getSignUp']);
Route::post('/ticket/activate',['before'=>'validated','uses'=>'TicketController@postSignUp']);
Route::get('/ticket/token/{token}','TicketController@getVerifyTicket');
//Route::get('/ticket/users',['before'=>'validated','uses'=>'TicketController@getUsers']);



Route::get('/pizza',['before'=>'has_admission','uses'=>'PizzaController@getSelectRound']);
Route::get('/pizza/{id}',['before'=>'has_admission','uses'=>'PizzaController@getSelectPizza']);
Route::post('/pizza/{id}',['before'=>'has_admission','uses'=>'PizzaController@postSelectPizza']);
Route::get('/pizza/{id}/confirm',['before'=>'has_admission','uses'=>'PizzaController@getConfirm']);
Route::get('/pizza/{id}/status',['before'=>'has_admission','uses'=>'PizzaController@getStatus']);
Route::post('/pizza/{id}/confirm',['before'=>'has_admission','uses'=>'PizzaController@postConfirm']);
Route::post('/pizza/{id}/delete',['before'=>'has_admission','uses'=>'PizzaController@postDelete']);
Route::post('/pizza/{id?}',['before'=>'has_admission','uses'=>'PizzaController@postOrder']);

Route::get('/chat',['before'=>'validated','uses'=>'ChatController@getFrame']);
Route::get('/prebind.json',['before'=>'validated','uses'=>'ChatController@getPrebind']);

Route::get('/seating/seats.json',['before'=>'auth','uses'=>'SeatController@getSeatsJson']);
Route::post('/seating/seats.json',['before'=>'has_admission','uses'=>'SeatController@postSeatsJson']);
Route::post('/seating/unseatme.json',['before'=>'has_admission','uses'=>'SeatController@postUnseatJson']);
Route::get('/seating',['before'=>'auth','uses'=>'SeatController@getSeating']);

Route::post('/tournaments/{id}/join',array('before'=>'has_admission','uses'=>'GamesController@postJoinTournament'));
Route::get('/tournaments/{id}',array('before'=>'has_admission','uses'=>'GamesController@getTournament'));
Route::get('/tournaments/{id}/team-list',array('before'=>'permission:tournaments','uses'=>'GamesController@getTeamList'));

Route::get('/tournaments/{id}/leave/{teamid?}',array('before'=>'has_admission','uses'=>'GamesController@getLeaveTournament'));
Route::get('/tournaments',array('before'=>'has_admission','uses'=>'GamesController@getTournaments'));

Route::get('/team/{team}',array('before'=>'validated','uses'=>'TeamController@getTeam'));
Route::post('/team/disband/{id}',array('before'=>'validated','uses'=>'TeamController@postDisbandTeam'));
Route::post('/team/leave/{id}',array('before'=>'validated','uses'=>'TeamController@postLeaveTeam'));
Route::post('/team/join/{id}',array('before'=>'validated','uses'=>'TeamController@postJoinTeam'));
Route::post('/team/accept/{id}',array('before'=>'validated','uses'=>'TeamController@postAcceptRequest'));
Route::post('/team/decline/{id}',array('before'=>'validated','uses'=>'TeamController@postDeclineRequest'));
Route::post('/team',array('before'=>'validated','uses'=>'TeamController@postTeam'));

Route::get('/auth/send-validation-email',array('before'=>'auth','uses'=>'UserController@getSendValidationEmail'));
Route::get('/auth/validate/{token}','UserController@getValidateLink');
Route::get('/auth/register','UserController@getRegister');
Route::post('/auth/register','UserController@postRegister');
Route::get('/auth/password-reminder','RemindersController@getRemind');
Route::post('/auth/password-reminder','RemindersController@postRemind');
Route::get('/auth/password-reset/{token}','RemindersController@getReset');
Route::post('/auth/password-reset','RemindersController@postReset');
Route::get('/auth/login','UserController@getLogin');
Route::post('/auth/login','UserController@postLogin');
Route::get('/auth/logout','UserController@logout');
Route::post('/profile/transfer_ticket',['before'=>'has_admission','uses'=>'UserController@postTransferTicket']);
Route::get('/profile',['before'=>'auth','uses'=>'UserController@getProfile']);
Route::get('/oauth/auth_complete',['before'=>'auth','uses'=>'PushbulletController@getAuthComplete']);

Route::post('/profile',['before'=>'auth','uses'=>'UserController@postProfile']);
Route::get('/pushbullet/test',['before'=>'auth','uses'=>'PushbulletController@getSendTest']);
Route::get('/pushbullet/status',['before'=>'auth','uses'=>'PushbulletController@getAuthStatus']);
Route::get('/halloffame',['uses'=>'HalloffameController@index']);
Route::get('/armband',['uses'=>'ArmbandController@index','before'=>'permission:users']);
Route::post('/armband',['uses'=>'ArmbandController@search','before'=>'permission:users']);

Route::get('/{older?}', ['as'=>'frontpage','uses'=>'HomeController@showWelcome']);
Route::post('/',['uses'=>'HomeController@postEntry','before'=>'permission:entries_edit']);

Route::post('/slack/command',['uses'=>'SlackController@slashCommand']);

// Route::get('/test',function() {
// 	$cropped = public_path().'/uploads/1140x760_crop/2kPACpaLYOCv85DLmIO1.jpg';
// 	$overlay = public_path().'/img/overlayMain.png';
// 	$overlayed = public_path().'/uploads/winners/2kPACpaLYOCv85DLmIO1.jpg';

// 	$img = Image::make($cropped);
// 	$img->text('Counter-Strike',570,600,function($font) {
// 		$font->file(base_path().'/Nunito-Bold.ttf');
// 		$font->size(60);
// 		$font->color('#ffffff');
// 		$font->align('center');
// 	});
// 	$img->text('1st place to KagerneDK',570,680,function($font) {
// 		$font->file(base_path().'/Nunito-Regular.ttf');
// 		$font->size(40);
// 		$font->color('#ffffff');
// 		$font->align('center');
// 	});
// 	$img->insert($overlay)->save($overlayed);

// });
//
