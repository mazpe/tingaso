@extends('layout')

@section('content')

<h2>Edit {{ $asterisk->name }}</h1>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($asterisk, array('route' => array('asterisk.update', $asterisk->id), 'method' => 'PUT')) }}

    <div class="form-group">
        {{ Form::label('name', 'Name') }}
        {{ Form::text('name', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('value', 'Value') }}
        {{ Form::text('value', null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit Asterisk Setting!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop
