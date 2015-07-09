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
    //Route::get('/',function() {
	//	return "Hello World";
	//});

	Route::get('/', 'HomeController@getHome');
	Route::get('/home', 'HomeController@getHome');

	Route::get('/reports', 'ReportsController@getReport');

	Route::get('/dialer/update_status','DialerController@update_status');
	Route::get('/dialer/session/{id}/update_status','DialerController@session_update_status');
	Route::post('/dialer/session/create','DialerController@session_create');
	Route::controller('dialer','DialerController');


});

Route::group(['prefix' => 'settings','before'=>'auth'], function(){
    Route::get('/caller_id', 'CallerIDController@getIndex');
    Route::post('/caller_id/create', 'CallerIDController@postCreate');
    Route::get('/caller_id/{id}/edit', 'CallerIDController@edit');
    Route::put('/caller_id/{id}/update',
        array('as' => 'caller_id.update',
            'uses' => 'CallerIDController@update'));
    Route::get('/caller_id/{id}/delete', 'CallerIDController@delete');

    Route::get('/asterisk', 'AsteriskController@index');
    Route::post('/asterisk/create', 'AsteriskController@create');
    Route::get('/asterisk/{id}/edit', 'AsteriskController@edit');
    Route::put('/asterisk/{id}/update',
        array('as' => 'asterisk.update',
            'uses' => 'AsteriskController@update'));
    Route::get('/asterisk/{id}/delete', 'AsteriskController@delete');

    Route::get('/settings/system/{id}/edit', 'SettingsController@settings_system_edit');
    //Route::controller('settings','SettingsController');
});


Route::get('/register','UsersController@getRegister');
Route::post('/register','UsersController@create');
Route::get('/login','UsersController@getLogin');
Route::post('/login','UsersController@postLogin');
Route::get('/logout',function() {
	Auth::logout();
	return Redirect::to('login');
});

