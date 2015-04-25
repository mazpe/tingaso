@extends('layout')

@section('content')

<h2>Edit {{ $dealer->name }}</h1>
<a href="{{ URL::to('dealers/'. $dealer->id .'/create_month') }}">
Create Month
</a>

<!-- if there are creation errors, they will show here -->
{{ HTML::ul($errors->all()) }}

{{ Form::model($setting, array('route' => array('dealers.update', $dealer->id), 'method' => 'PUT')) }}


    <div class="form-group">
        {{ Form::label('hours_of_operation', 'Hours Of Operation') }}
        {{ Form::text('hours_of_operation', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('default_rate', 'Default Rate') }}
        {{ Form::text('default_rate', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('default_records', 'Default Records') }}
        {{ Form::text('default_records', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('appt_recipients', 'Appt Recipients') }}
        {{ Form::text('appt_recipients', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('avg_ro', 'Averge RO') }}
        {{ Form::text('avg_ro', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('ro_percent', 'RO Percent') }}
        {{ Form::text('ro_percent', null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('added_by_id', 'Added By') }}
        {{ Form::select('added_by_id', $added_by , null, array('class' => 'form-control')) }}
    </div>

    <div class="form-group">
        {{ Form::label('active', 'Active') }}
        {{ Form::select('active', array('1' => 'Active', '2' => 'Inactive'), null, array('class' => 'form-control')) }}
    </div>

    {{ Form::submit('Edit Settings!', array('class' => 'btn btn-primary')) }}

{{ Form::close() }}

@stop
