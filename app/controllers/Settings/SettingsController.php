<?php

class SettingsController extends \BaseController {

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

    public function getSystem()
    {
        $settings = DB::table('settings')->orderBy('id','desc')->get();

        return View::make('settings.system')
            ->with(['settings' => $settings]);
    }

    public function settings_system_edit($id)
    {
        $setting = DB::table('settings')->find($id);

        return View::make('settings.system_edit')
            ->with(['setting' => $setting]);
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
            'caller_id' => 'required|numeric|min:3',
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
            $caller_id  = Input::get('caller_id');
            $area_code  = Input::get('area_code');
            $prefix     = Input::get('prefix');
            $starting   = Input::get('starting');
            $ending     = Input::get('ending');

            $session_id = DB::table('dialing_sessions')->insertGetId(
                array(
                    'caller_id'     => Input::get('caller_id'),
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


                $phone_number = array(
                    'area_code'     => $area_code,
                    'prefix'        => $prefix,
                    'number'        => $number,
                    'status'        => 'Not Called',
                    'session_id'    => $session_id
                );

                array_push($phone_numbers, $phone_number);

                //echo $number."<br>";
                $number++;
            }

            DB::table('phone_numbers')->insert($phone_numbers);


            $not_called_count = DB::table('phone_numbers')
                ->where('session_id',$session_id)
                ->where('status','Not Called')
                ->count();


            while ($not_called_count != 0 ) {

                echo $not_called_count."<br><br>";

                $phone_number = DB::table('phone_numbers')
                    ->where('session_id',$session_id)
                    ->where('status','Not Called')
                    ->orderBy(DB::raw('RAND()'))
                    //->take(1)
                    ->first();
                ;
                echo $phone_number->number ."<br>"; 

                $call_number = $phone_number->area_code.$phone_number->prefix.$phone_number->number;

                $this->generate_call_files($call_number);

                DB::table('phone_numbers')
                    ->where('id',$phone_number->id)
                    ->update(['Status' => 'Call File Created']);

                $not_called_count = DB::table('phone_numbers')
                    ->where('session_id',$session_id)
                    ->where('status','Not Called')
                    ->count()
                ;

            }


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

    public function getSession($id)
    {
                //
        $call_session = DB::table('dialing_sessions')->find($id);
        $phone_numbers = DB::table('phone_numbers')->where('session_id',$id)->get();
        
        return View::make('dialer.session')
            ->with([
                'call_session' => $call_session,
                'phone_numbers' => $phone_numbers,
            ]);


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

        echo $file_path."<br>";
        exec("mv $file_path /var/spool/asterisk/outgoing2");
    }

}
