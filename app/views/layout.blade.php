<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tingao - El dialer mas enpingao!</title>

    <!-- Bootstrap CSS served from a CDN -->
    <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootswatch/3.1.0/superhero/bootstrap.min.css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <style>
    body{
      background: url("/img/stardust.png");
        padding-top: 70px;
    }

    .centered-form .panel{
      background: rgba(255, 255, 255, 0.8);
      box-shadow: rgba(0, 0, 0, 0.3) 20px 20px 20px;
      color: #4e5d6c;
    }

    .centered-form{
      margin-top: 60px;
    }
    </style>
  </head>

  <body>

     @if (Auth::check())
        @include('admin_nav')
     @endif

    <div class="container">
      @if(Session::get('errors'))
        <div class="alert alert-danger alert-dismissable">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h5>The following errors occurred:</h5>
          @foreach($errors->all('<li>:message</li>') as $message)
            {{$message}}
          @endforeach
        </div>
      @endif

      @if(Session::has('message'))
      <p class="alert alert-info">{{ Session::get('message') }}</p>
      @endif
    </div>

    <div class="container">
         @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.0/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
    {{ HTML::script('js/charts.js') }}
  </body>
</html>