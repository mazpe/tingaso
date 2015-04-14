@extends('layout')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Dialer</h1>

        <h2><font color="#ff4500"> Create a Dialing Sessions</font></h2>
        <p>Enter the area code, prefix, starting and ending range to create a dialing session</p>

        {{ Form::open(array('url'=>'/dialer/session/create','class'=>'form-inline')) }}

        <div class="container-fluid">
            <div class="col-xs-1 ">Range: </div>
            <div class="col-xs-11">
                <div class="form-group">
                    <input type="text" name="area_code" class="form-control" id="area_code" placeholder="305">
                </div>
                <div class="form-group">
                    <input type="text" name="prefix" class="form-control" id="prefix" placeholder="356">
                </div>
                Starting:
                <div class="form-group">
                    <input type="text" name="starting" class="form-control" id="starting" placeholder="1000">
                </div>
                Ending:
                <div class="form-group">
                    <input type="text" name="ending" class="form-control" id="ending" placeholder="1099">
                </div>
            </div>
        </div>
        <br />
        <p>
          <button class="btn btn-lg btn-primary" type="submit">Create Dialing Session &raquo;</button>
        </p>
        <br />
        <h2><font color="#ff4500">Active Sessions</font></h2>
          <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
              <tr >
                <th>ID</th>
                <th>Area/Prefix</th>
                <th>Starting</th>
                <th>Ending</th>
                <th>Status</th>
                <th>Total</th>
                <th>Pending</th>
                <th>Completed</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
@foreach ($dialing_sessions as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->area_code }}-{{ $c->prefix }}</td>
                <td>{{ $c->starting }}</td>
                <td>{{ $c->ending }}</td>
                <td>{{ $c->status }}</td>
                <td>{{ $c->ending - $c->starting }}</td>
                <td></td>
                <td></td>
                <td>{{ $c->created_by_id }}</td>
                <td>{{ $c->created_at }}</td>
                <td>
                <span class="glyphicon glyphicon-pencil" style="padding-right: 10px" title="Edit"></span>
                <span class="glyphicon glyphicon-trash" title="Remove"></span>
                </td>
              </tr>
@endforeach
            </tbody>
          </table>

      </div>


@stop