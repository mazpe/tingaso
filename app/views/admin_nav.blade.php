@section('admin_nav')
<?php
$controller = explode("@",Route::currentRouteAction());
$controller = $controller[0];
?>
    <!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><font color="#ff4500" size="12"><strong>Tingaso</strong></font></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li @if ($controller == "HomeController") class="active" @endif><a href="/home">Home</a></li>
            <li @if ($controller == "DialerController") class="active" @endif><a href="/dialer">Dialer</a></li>
            <li @if ($controller == "ReportsController") class="active" @endif><a href="/reports">Reports</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Settings <span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
                <li><a href="#">Users</a></li>
                <li class="divider"></li>
                <li><a href="/settings/dialer">Dialers</a></li>
                <li><a href="/settings/asterisk">Asterisk Settings</a></li>
                <li><a href="/settings/caller_id">Caller IDs</a></li>
              </ul>
            </li>
            <li><a href="/logout">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}<span class="sr-only"></span></a></li>
            <li class="active"><a href="/logout">Logout <span class="sr-only">(current)</span></a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
@show
