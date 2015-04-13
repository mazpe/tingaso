<?php

class HomeController extends BaseController {

	public function showWelcome()
	{
		return View::make('hello');
	}

    public function getLogin() {
        return View::make('login');
    }

    public function postLogin()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'username' => 'required',
            'password' => 'required|alphaNum|min:3'
        );
        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('login')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // create our user data for the authentication
            $userdata = array(
                'username'     => Input::get('username'),
                'password'  => Input::get('password')
            );
            // attempt to do the login
            if (Auth::attempt($userdata)) {
                // validation successful!
                return Redirect::to('/dialer');
            } else {
                // validation not successful, send back to form
                return Redirect::to('login');
            }
        }
    }
    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

}
