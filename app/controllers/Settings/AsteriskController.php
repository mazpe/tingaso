<?php

class AsteriskController extends BaseController {

	public function index()
	{
        $asterisk = DB::table('asterisk')->orderBy('id','desc')->get();

        return View::make('settings.asterisk')
            ->with(['asterisk' => $asterisk]);
	}

    public function create()
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'name'      => 'required',
            'value'     => 'required',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('/system/asterisk')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            // create session in database
            $name   = Input::get('name');
            $value  = Input::get('value');

            $asterisk = DB::table('asterisk')->insertGetId(
                array(
                    'name'  => $name,
                    'value' => $value,
                    'created_by_id' => Auth::user()->id
                )
            );
        }

        Session::flash('message', 'Asterisk setting was added.');
        return Redirect::to('/settings/asterisk');

    }

    public function edit($id)
    {
        $caller_id = CallerID::find($id);

        return View::make('settings.caller_id_edit')
            ->with(['caller_id' => $caller_id]);
    }

    public function update($id)
    {
        // validate the info, create rules for the inputs
        $rules = array(
            'area_code' => 'required|numeric|min:3',
            'prefix'    => 'required|numeric|min:3',
            'number'    => 'required|numeric|min:4',
        );

        // run the validation rules on the inputs from the form
        $validator = Validator::make(Input::all(), $rules);
        // if the validator fails, redirect back to the form
        if ($validator->fails()) {
            return Redirect::to('/system/caller_id/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            // create session in database
            $area_code = Input::get('area_code');
            $prefix = Input::get('prefix');
            $number = Input::get('number');

            $caller_id = CallerID::find($id);
            $caller_id->update(
                array(
                    'area_code' => $area_code,
                    'prefix' => $prefix,
                    'number' => $number,
                    'full_number' => $area_code . $prefix . $number,
                    'status' => 'Not Used',
                    'updated_by_id' => Auth::user()->id
                )
            );
        }

        Session::flash('message', 'Caller ID was updated.');
        return Redirect::to('/settings/caller_id');

    }

    public function delete($id)
    {
        $asterisk = Asterisk::find($id);
        $asterisk->delete();

        Session::flash('message', 'Asterisk Setting was deleted.');
        return Redirect::to('/settings/asterisk');
    }


}
