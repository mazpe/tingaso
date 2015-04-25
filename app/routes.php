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

Route::group([ 'before' => 'auth' ], function() { 
	Route::get('/',function() {
		return "Hello World";
	});

	Route::get('/home', 'HomeController@getHome');

	Route::get('/reports', 'ReportsController@getReport');

	Route::post('/dialer/session/create','DialerController@session_create');
	Route::controller('dialer','DialerController');

    Route::group(['prefix' => 'settings','before'=>'auth'], function(){
	    Route::get('caller_id', 'CallerIDController@getIndex');
	    Route::get('/settings/system/{id}/edit', 'SettingsController@settings_system_edit');
	    Route::controller('settings','SettingsController');
    });

});

Route::get('/register','UsersController@getRegister');
Route::post('/register','UsersController@create');
Route::get('/login','UsersController@getLogin');
Route::post('/login','UsersController@postLogin');
Route::get('/logout',function() {
	Auth::logout();
	return Redirect::to('login');
});

