<?php

class AsteriskController extends BaseController {

	public function index()
	{
        $asterisk = DB::table('asterisk')->orderBy('id','asc')->get();

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
        $asterisk = DB::table('asterisk')->where('id',$id)->first();

        return View::make('settings.asterisk_edit')
            ->with(['asterisk' => $asterisk]);
    }

    public function update($id)
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
            return Redirect::to('/system/asterisk/'.$id.'/edit')
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {

            // create session in database
            $name   = Input::get('name');
            $value  = Input::get('value');

            $asterisk = DB::table('asterisk')->where('id',$id); 
            $asterisk->update(
                array(
                    'name'  => $name,
                    'value' => $value,
                    'updated_by_id' => Auth::user()->id
                )
            );
        }

        Session::flash('message', 'Asterisk Setting was updated.');
        return Redirect::to('/settings/asterisk');

    }

    public function delete($id)
    {
        $asterisk = Asterisk::find($id);
        $asterisk->delete();

        Session::flash('message', 'Asterisk Setting was deleted.');
        return Redirect::to('/settings/asterisk');
    }


}
