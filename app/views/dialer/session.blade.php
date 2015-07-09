@extends('layout')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
      <div class="jumbotron">
        <h1>Dialer :: Session</h1>

        <h2><font color="#ff4500">Sessions ID: {{ $call_session->id }} </font></h2>

        <h2><font color="#ff4500">Phone Numbers</font></h2>
          <table class="table table-condensed table-striped table-bordered table-hover">
            <thead>
              <tr >
                <th>ID</th>
                <th>Phone</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
@foreach ($phone_numbers as $c)
              <tr>
                <td>{{ $c->id }}</td>
                <td>{{ $c->area_code }}-{{ $c->prefix }}-{{ $c->number }}</td>
                <td>{{ $c->status }}</td>
                <td>{{ $c->created_at }}</td>
                <td>{{ $c->updated_at }}</td>
                <td>
                <span class="glyphicon glyphicon-pencil" style="padding-right: 10px" title="Edit"></span>
                <span class="glyphicon glyphicon-trash" title="Remove"></span>
                </td>
              </tr>
@endforeach
            </tbody>
          </table>




@stop
