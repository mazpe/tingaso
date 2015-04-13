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

Route::get('/', function()
{
	return View::make('index');
});

Route::get('/home', 'HomeController@getHome');

Route::get('/reports', 'ReportsController@getReport');

Route::get('/register','UsersController@getRegister');
Route::post('/register','UsersController@create');
Route::get('/login','UsersController@getLogin');
Route::post('/login','UsersController@postLogin');
Route::get('/logout',function() {
    Auth::logout();
    return Redirect::to('login');
});

Route::controller('dialer','DialerController');

