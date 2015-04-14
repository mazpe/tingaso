<?php

class DialerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $dialing_sessions = DB::table('dialing_sessions')->orderBy('id','desc')->get();

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
                    'status'        => 'Incomplete',
                    'created_by_id' => Auth::user()->id,
                    'updated_by_id' => Auth::user()->id
                )
            );

            // insert each number in database
            $phone_numbers = [];
            $number = $starting;
            for ($i = $starting; $i <= $ending; $i++) {

                $this->generate_call_files($area_code.$prefix.$number);

                $phone_number = array(
                    'area_code'     => $area_code,
                    'prefix'        => $prefix,
                    'number'        => $number,
                    'status'        => 'Not Called',
                    'session_id'    => $session_id
                );

                array_push($phone_numbers, $phone_number);

                echo $number."<br>";
                $number++;
            }

            DB::table('phone_numbers')->insert($phone_numbers);

            // return to dialers page
            Session::flash('message', 'Dialing Session has started. Session ID: '. $session_id);
            return Redirect::to('/dialer');

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

   protected function generate_call_files($phone_number)
    {
        $file_path = "/tmp/".$phone_number.".call";
        $handle = fopen($file_path,"w");

        $contents = "Channel: SIP/" . $phone_number . "@vitel-outbound
            Callerid: 7861234567
            MaxRetries: 5
            RetryTime: 600
            WaitTime: 15
            Application: hangup
            Archive: yes
            ";

        fwrite($handle,$contents);

        fclose($handle);

        //echo $file_path."<br>";
        //exec("mv $file_path /var/spool/asterisk/outgoing");
    }

}
