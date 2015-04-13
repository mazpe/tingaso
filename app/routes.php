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

Route::get('/login','HomeController@login');
Route::post('/login','HomeController@doLogin');
Route::get('/logout',function() {
    Auth::logout();
    return Redirect::to('login');
});

Route::controller('dialer','DialerController');

