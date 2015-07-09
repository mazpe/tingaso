@extends('layout')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Dialer</h1>

        <h2>Create a Dialing Sessions</h2>
        <p>Enter the area code, prefix, starting and ending range to create a dialing session</p>

        {{ Form::open(array('url'=>'/dialer/session/create','class'=>'form-inline')) }}

        <h3>Enter Caller ID:</h3>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::select('caller_id_id', $caller_ids , Input::old('caller_id_id'), array('class' => 'form-control')) }}
                    </div>
                </div>
            </div>
        </div>
<p></p>

        <h3>Enter Calling Range:</h3>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <input type="text" name="area_code" class="form-control" id="area_code" placeholder="Area Code">
                    </div>
                    <div class="form-group">
                        <input type="text" name="prefix" class="form-control" id="prefix" placeholder="Prefix">
                    </div>
                    <div class="form-group">
                        <input type="text" name="starting" class="form-control" id="starting" placeholder="Starting">
                    </div>
                    <div class="form-group">
                        <input type="text" name="ending" class="form-control" id="ending" placeholder="Ending">
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                <button class="btn btn-lg btn-primary" type="submit">Create Dialing Session &raquo;</button>
                </div>
            </div>
        </div>

        <br />
        <h2><font color="#ff4500">Active Sessions</font></h2>
            <div class="row">
                <div class="col-md-4">
                <a href="/dialer/update_status" class="btn btn-lg btn-primary" role="button">Update Status &raquo;</a>
                </div>
            </div>
          <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
              <tr >
                <th>ID</th>
                <th>Area/Prefix</th>
                <th>Starting</th>
                <th>Ending</th>
                <th>Status</th>
                <th>Total</th>
                <th>Called</th>
                <th>Completed</th>
                <th>Queued</th>
                <th>Calling</th>
                <th>Expired</th>
                <th>Failed</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
@foreach ($dialing_sessions as $c)
              <tr>
                <td><a href="/dialer/session/{{ $c->id }}">{{ $c->id }}</a></td>
                <td>{{ $c->area_code }}-{{ $c->prefix }}</td>
                <td>{{ $c->starting }}</td>
                <td>{{ $c->ending }}</td>
                <td>{{ $c->status }}</td>
                <td align="center">{{ $c->total }}</td>
                <td align="center">{{ $c->completed + $c->expired + $c->failed }}</td>
                <td align="center">{{ $c->completed }}</td>
                <td align="center">{{ $c->queued }}</td>
                <td align="center">{{ $c->calling }}</td>
                <td align="center">{{ $c->expired }}</td>
                <td align="center">{{ $c->failed }}</td>
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
{{ $dialing_sessions->links() }}

@stop
