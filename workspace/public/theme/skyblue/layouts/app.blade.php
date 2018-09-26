<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- core CSS -->
        <!-- Latest compiled and minified CSS -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/main.css" rel="stylesheet">
        <!--<link href="{{ url('/theme') }}/{{$theme}}/css/prettyPhoto.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/responsive.css" rel="stylesheet">-->
        <!--[if lt IE 9]>
        <script src="{{ url('/theme') }}/{{$theme}}/{{ url('/theme') }}/{{$theme}}/js/html5shiv.js"></script>
        <script src="{{ url('/theme') }}/{{$theme}}/{{ url('/theme') }}/{{$theme}}/js/respond.min.js"></script>
        <![endif]-->
        <link rel="shortcut icon" href="{{ url('/theme') }}/{{$theme}}/images/ico/favicon.ico">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ url('/theme') }}/{{$theme}}/images/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ url('/theme') }}/{{$theme}}/images/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ url('/theme') }}/{{$theme}}/images/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="{{ url('/theme') }}/{{$theme}}/images/ico/apple-touch-icon-57-precomposed.png">

        @if(isset($cssFiles))
        @foreach($cssFiles as $src)
        <link href="{{$src}}{{$cssTimeStamp}}" rel="stylesheet" type="text/css" />
        @endforeach
        @endif
        <script>
            var themePath = "{{$url}}/theme/{{$theme}}/";
            var siteUrl = "{{$url}}";
        </script>

    </head><!--/head-->

    <body class="homepage">
        <header id="header">
            <nav class="navbar navbar-inverse" role="banner">
                <div class="">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="{{url('/')}}"><img src="{{ url('/theme') }}/{{$theme}}/images/logo.png" alt="logo"></a>
                    </div>
                </div><!--/.container-->
            </nav><!--/nav-->

        </header><!--/header-->

        @yield('content')

        <footer id="footer" class="midnight-blue">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6">
                        &copy; {{ date("Y") }}
                        <p>Copyright © 2018 Silicus Technologies, LLC </p>
                    </div>
                    <div class="col-sm-6">
                        <ul class="pull-right">
                            <li><a href="{{url('/')}}">Home</a></li>
                            <li><a href="{{url('/about-us')}}">About Us</a></li>
                            <li><a href="{{ url('/faqs') }}">FAQs</a></li>
                            <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer><!--/#footer-->
        <div id="application-message">
            @if (isset($successMessage) || Session::has('successMessage'))
            <div class="alert alert-success">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> {{ isset($successMessage) ? $successMessage : Session::get('successMessage') }}
            </div>
            @endif

            @if (isset($infoMessage) || Session::has('infoMessage'))
            <div class="alert alert-info">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Info!</strong> {{ isset($infoMessage) ? $infoMessage : Session::get('infoMessage') }}
            </div>
            @endif

            @if (isset($warningMessage) || Session::has('warningMessage'))
            <div class="alert alert-warning">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Warning!</strong> {{ isset($warningMessage) ? $warningMessage : Session::get('warningMessage') }}
            </div>
            @endif

            @if (isset($errorMessage) || Session::has('errorMessage'))
            <div class="alert alert-danger">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Danger!</strong> {{ isset($errorMessage) ? $errorMessage : Session::get('errorMessage') }}
            </div>
            @endif
        </div>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script src="{{ url('/theme') }}/{{$theme}}/js/main.js"></script>
        <script src="{{ url('/theme') }}/{{$theme}}/js/menu.js"></script>
        @if(isset($jsFiles))
        @foreach($jsFiles as $src)
        <script src="{{$src}}"></script>
        @endforeach
        @endif

    </body>
</html>