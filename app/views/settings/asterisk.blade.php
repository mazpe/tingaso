@extends('layout')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Asterisk Settings</h1>

        <p>Enter numbers to be added as caller ids and manage them.</p>

        {{ Form::open(array('url'=>'/settings/asterisk/create','class'=>'form-inline')) }}
        <h3>Create Settings:</h3>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="form-group">
                        <input type="text" name="name" class="form-control" id="name" placeholder="Name">
                    </div>
                    <div class="form-group">
                        <input type="text" name="value" class="form-control" id="value" placeholder="Value">
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                <button class="btn btn-lg btn-primary" type="submit">Create Asterisk Setting &raquo;</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}

        <br />
        <h2><font color="#ff4500">IDs</font></h2>
          <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
              <tr >
                <th>ID</th>
                <th>Name</th>
                <th>Value</th>
                <th>Created By</th>
                <th>Updated By</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
@foreach ($asterisk as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->name }}</td>
                <td>{{ $c->value }}</td>
                <td>{{ $c->created_by_id }}</td>
                <td>{{ $c->updated_by_id }}</td>
                <td>{{ $c->created_at }}</td>
                <td>{{ $c->updated_at }}</td>
                <td>
                <a href="/settings/asterisk/{{ $c->id }}/edit">
                    <span class="glyphicon glyphicon-pencil" style="padding-right: 10px" title="Edit"></span>
                </a>
                <a href="/settings/asterisk/{{ $c->id }}/delete">
                    <span class="glyphicon glyphicon-trash" title="Remove"></span>
                </a>
                </td>
              </tr>
@endforeach
            </tbody>
          </table>

@stop
