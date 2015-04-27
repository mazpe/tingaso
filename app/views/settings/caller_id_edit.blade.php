@extends('layout')

@section('content')

<h2>Edit {{ $caller_id->full_number }}</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($caller_id, array('route' => array('caller_id.update', $caller_id->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('area_code', 'Area Code') }}
        {{ Form::text('area_code', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('prefix', 'Prefix') }}
        {{ Form::text('prefix', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('number', 'Number') }}
        {{ Form::text('number', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('status', 'Status') }}
        {{ Form::text('status', null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit Caller ID!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop
