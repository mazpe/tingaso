@extends('layout')

@section('content')

      <!-- Main component for a primary marketing message or call to action -->
    <div class="jumbotron">
        <h1>Home</h1>
        <p>Dialers</p>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6"> <div id="line-chart" style="height: 250px;"></div> </div>
                <div class="col-xs-6"> <div id="area-chart" style="height: 250px;"></div> </div>
            </div>
        </div>

        <p>Trunks</p>
        <div class="container-fluid">
            <div class="row">
                <div class="col-xs-6"> <div id="bar-chart" style="height: 250px;"></div> </div>
                <div class="col-xs-6"> <div id="donut-chart" style="height: 250px;"></div> </div>
            </div>
        </div>

    <div>

@stop