<?php

class DialerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $dialing_sessions = DB::table('dialing_sessions')->get();

		return View::make('dialer.index')
            ->with(['dialing_sessions' => $dialing_sessions]);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function session_create()
	{
        // validate the info, create rules for the inputs
        $rules = array(
            'area_code' => 'required|numeric|min:3',
            'prefix'    => 'required|numeric|min:3',
            'starting'  => 'required|numeric|min:4',
            'ending'    => 'required|numeric|min:4'
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('/dialer')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            // create session in database
            $area_code  = Input::get('area_code');
            $prefix     = Input::get('prefix');
            $starting   = Input::get('starting');
            $ending     = Input::get('ending');

            $session_id = DB::table('dialing_sessions')->insertGetId(
                array(
                    'area_code'     => Input::get('area_code'),
                    'prefix'        => Input::get('prefix'),
                    'starting'      => $starting,
                    'ending'        => $ending,
                    'created_by_id' => Auth::user()->id,
                    'updated_by_id' => Auth::user()->id
                )
            );

            // insert each number in database
            $phone_numbers = [];
            $number = $starting;
            for ($i = $starting; $i <= $ending; $i++) {

                $phone_number = array(
                    'area_code'     => $area_code,
                    'prefix'        => $prefix,
                    'number'        => $number,
                    'session_id'    => $session_id
                );

                array_push($phone_numbers, $phone_number);

                echo $number."<br>";
                $number++;
            }

            DB::table('phone_numbers')->insert($phone_numbers);

            // create call file per number

            // return to dialers page
            Session::flash('message', 'Dialing Session has started. Session ID: '. $session_id);
            //return Redirect::to('/dialer');

        }

    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
