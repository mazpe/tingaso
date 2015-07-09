<?php

class DialerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function getIndex()
	{
        $dialing_sessions = DB::table('dialing_sessions')->orderBy('id','desc')->paginate(50);

        $caller_ids = array('' => 'Select a Caller ID') +
            DB::table('caller_ids')->lists('full_number', 'id');

		return View::make('dialer.index')
            ->with([
                'dialing_sessions' => $dialing_sessions,
                'caller_ids'        => $caller_ids,
            ]);
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
            'caller_id_id' => 'required|numeric',
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
            $caller_id_id   = Input::get('caller_id_id');
            $area_code      = Input::get('area_code');
            $prefix         = Input::get('prefix');
            $starting       = Input::get('starting');
            $ending         = Input::get('ending');

            if ($starting > $ending) {
                Session::flash('message', 'Starting number ('.$starting.') cannot be greater than Ending number ('.$ending .')');
                return Redirect::to('/dialer');
            }

            $session_id = DB::table('dialing_sessions')->insertGetId(
                array(
                    'caller_id_id'  => $caller_id_id,
                    'area_code'     => $area_code,
                    'prefix'        => $prefix,
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
            $counter = 0;
            for ($i = $starting; $i <= $ending; $i++) {


                $phone_number = array(
                    'area_code'     => $area_code,
                    'prefix'        => $prefix,
                    'number'        => $number,
                    'status'        => 'Queued',
                    'session_id'    => $session_id
                );

                array_push($phone_numbers, $phone_number);

                //echo $number."<br>";
                $number++;
                $counter++;
            }

            DB::table('phone_numbers')->insert($phone_numbers);

            DB::table('dialing_sessions')->where('id',$session_id)->update(['total' => $counter]);

            // return to dialers page
            Session::flash('message', 'Dialing Session has started. Session ID: '. $session_id);
            return Redirect::to('/dialer');

        }

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

    public function update_status()
    {

        $numbers = DB::table('phone_numbers')->where('status','!=','Completed')->get();

        $path = "/var/spool/asterisk/outgoing_done/";
        ////echo "path: $path<br>";

        foreach( $numbers as $key => $value ) {

            $phone_number = $value->area_code.$value->prefix.$value->number;
            $file = $path.$phone_number."-SID".$value->session_id.".call";
            ////echo "phone_number: $phone_number<br>";
            ////echo "file: $file<br>";
            
            //check if exists in outgoing_done
            if (file_exists($file)) {

                //open file and search for status line
                $lines = file($file);

                foreach ($lines as $line_num => $line) {
                    $file_lines = explode(": ", $line);

                    if (count($file_lines) > 1) {

                        if ($file_lines[0] == "Status") {
                            $phone_number = DB::table('phone_numbers')
                                ->where('id',$value->id)
                                ->update(['status' => trim($file_lines[1])])
                                ;

                            $cmd = "sudo rm -Rf $file";
                            //exec($cmd. " > /dev/null &");

                        }

                    } else {
                        //echo " - not empty - ";
                    }

                }


                //read actual status and save to database

            } else {

                //echo $file ."-  doesnt exists <br>";

            }
        }

        // update sessions status count

        $dialing_sessions = DB::table('dialing_sessions')
            ->where('status','!=','Completed')
            ->get()
        ;

        //echo "updating session status<br>";
        foreach($dialing_sessions as $key => $value) {
            $id = $value->id;
            //echo "session id: $id<br>";

            //echo "updating status count<br>";
            $this->update_status_count($value->id,'Completed','completed');
            $this->update_status_count($value->id,'Queued','queued');
            $this->update_status_count($value->id,'Call File Generated','calling');
            $this->update_status_count($value->id,'Expired','expired');
            $this->update_status_count($value->id,'Failed','failed');

            //check if all numbers in dial session are completed... then mark session completed.
            $phone_numbers_count = DB::table('phone_numbers')
                ->where('session_id',$value->id)
                ->where(function ($query) {
                    $query->where('status','=','Call File Created')
                          ->orWhere('status','=','Queued');
                })
                ->count()
            ;
            //echo $phone_numbers_count ."<br>";

            if ($phone_numbers_count == 0) {
                DB::table('dialing_sessions')->where('id',$value->id)->update(['status' => 'Completed']);
            } else {
                //echo "not completed: ". $phone_numbers_count ."<br>";
            }
        }

        return Redirect::to('dialer');

    }

    public function session_update_status1($id)
    {
        $numbers = DB::table('phone_numbers')->where('session_id',$id)->get(); 

        $path = "/var/spool/asterisk/outgoing_done/";

        //var_dump($numbers);

        //loop through each number
        foreach( $numbers as $key => $value ) {

            $phone_number = $value->area_code.$value->prefix.$value->number;
            $file = $path.$phone_number."-SID".$id.".call";

            //check if exists in outgoing_done
            if (file_exists($file)) {

                //open file and search for status line
                $lines = file($file);

                foreach ($lines as $line_num => $line) {
                    $file_lines = explode(": ", $line);

                    if (count($file_lines) > 1) {

                        if ($file_lines[0] == "Status") {
                            $phone_number = DB::table('phone_numbers')
                                ->where('id',$value->id)
                                ->update(['status' => $file_lines[1]])
                                ;

                            $cmd = "sudo rm -Rf $file";
                            exec($cmd. " > /dev/null &");

                        }

                    } else {
                        //echo " - not empty - ";
                    }

                }


                //read actual status and save to database

            } else {

                //echo $file ."-  doesnt exists <br>";

            }
        }

        //check if all numbers in dial session are completed... then mark session completed.
        $phone_numbers_count = DB::table('phone_numbers')
            ->where('session_id',$id)
            ->where('status','=','Call File Created')
            ->count()
        ;
//echo $phone_numbers_count ."<br>";

        if ($phone_numbers_count == 0) {
            DB::table('dialing_sessions')->where('id',$id)->update(['status' => 'Completed']);
        }

        $call_session = DB::table('dialing_sessions')->find($id);
        $phone_numbers = DB::table('phone_numbers')->where('session_id',$id)->get();

        return View::make('dialer.session')
            ->with([
                'call_session' => $call_session,
                'phone_numbers' => $phone_numbers,
            ]);

    }


   protected function generate_call_files($session_id,$phone_number,$caller_id)
    {
        $asterisk = DB::table('asterisk')->where('name','MaxRetries')->first();
        $max_retries = $asterisk->value;
        $asterisk = DB::table('asterisk')->where('name','RetryTime')->first();
        $retry_time = $asterisk->value;
        $asterisk = DB::table('asterisk')->where('name','WaitTime')->first();
        $wait_time = $asterisk->value;

        $file_path = "/tmp/".$phone_number."-SID".$session_id.".call";
        $handle = fopen($file_path,"w");

        $contents = "Channel: SIP/" . $phone_number . "@AlcazarNetDialer\n".
            "Callerid: ".$caller_id."\n".
            "MaxRetries: $max_retries\n".
            "RetryTime: $retry_time\n".
            "WaitTime: $wait_time\n".
            "Application: hangup\n".
            "Archive: Yes\n";

        fwrite($handle,$contents);

        fclose($handle);

        echo $file_path."<br>";
        exec("mv $file_path /var/spool/asterisk/outgoing");
    }

    protected function update_status_count($session_id,$phone_status,$session_status)
    {

        //echo "updating status count: $session_id - $phone_status<br>";
        $calls = DB::table('phone_numbers')
            ->select('session_id',DB::raw('COUNT(*) as count'))
            ->where('session_id',$session_id)
            ->where('status',$phone_status)
            ->get()
        ;

        if ($calls) {
            foreach ($calls as $key => $value) {
                DB::table('dialing_sessions')->where('id',$session_id)->update(
                    [$session_status => $value->count]
                );
            }
        } else {

            DB::table('dialing_sessions')->where('id',$session_id)->update(
                [$session_status => 0]
            );

        }

        return;
    }

}
