@extends('layout')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Dialer</h1>

        <h2><font color="#ff4500"> Create a Dialing Sessions</font></h2>
        <p>Enter the area code, prefix, starting and ending range to create a dialing session</p>

        {{ Form::open(array('url'=>'/dialer/session/create','class'=>'form-inline')) }}

        <br />
        <h2><font color="#ff4500">Settings</font></h2>
          <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
              <tr >
                <th>ID</th>
                <th>Name</th>
                <th>Value</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
@foreach ($settings as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->name }}</td>
                <td>{{ $c->value }}</td>
                <td>
                <a href="/settings/system/{{ $c->id }}/edit">
                    <span class="glyphicon glyphicon-pencil" style="padding-right: 10px" title="Edit"></span>
                </a>
                <span class="glyphicon glyphicon-trash" title="Remove"></span>
                </td>
              </tr>
@endforeach
            </tbody>
          </table>




@stop
