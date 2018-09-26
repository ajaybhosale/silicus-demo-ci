<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        {!! Seo::render() !!}
        <!-- core CSS -->
        <!-- Latest compiled and minified CSS -->
        <link href="{{ url('/theme') }}/{{$theme}}/css/bootstrap.min.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/font-awesome.min.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/animate.min.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/prettyPhoto.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/main.css" rel="stylesheet">
        <link href="{{ url('/theme') }}/{{$theme}}/css/responsive.css" rel="stylesheet">
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
            var themePath = "{{$url}}theme/{{$theme}}/";
            var siteUrl = "{{$url}}";
        </script>

    </head><!--/head-->

    <body class="homepage">
        <header id="header">
            <div class="top-bar">
                <div class="container">

                    <div class="row">
                        <div class="col-sm-6 col-xs-4">
                            <div class="top-number"><p><i class="fa fa-phone-square"></i>  +0123 456 70 900</p></div>
                        </div>
                        <div class="col-sm-6 col-xs-8">
                            <div class="social">
                                <ul class="social-share">
                                    <li><a href="https://www.facebook.com/visithiltonhead?ref=ts&v=wall" title="Facebook"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="https://twitter.com/hiltonheadsc/" title="Twitter"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="https://www.youtube.com/user/hiltonheadislandvcb/" title="Youtube"><i class="fa fa-youtube"></i></a></li>
                                    <li><a href="https://instagram.com/visithiltonhead/" title="Instagram"><i class="fa fa-instagram"></i></a></li>
                                    <!-- <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                                    <li><a href="#"><i class="fa fa-skype"></i></a></li>-->
                                    @if (Auth::guest())
                                    <li><a href="{{ url('/login') }}" title="Login"><i class="fa fa-sign-in"></i></a></li>
                                    <li><a href="{{ url('/register') }}" title="Register"><i class="fa fa-dribbble"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div><!--/.container-->
            </div><!--/.top-bar-->

            <nav class="navbar navbar-inverse" role="banner">
                <div class="container">
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
                        All Rights Reserved.
                    </div>
                </div>
            </div>
        </footer><!--/#footer-->

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="{{ url('/theme') }}/{{$theme}}/js/bootstrap.min.js"></script>
        @if(isset($jsFiles))
        @foreach($jsFiles as $src)
        <script src="{{$src}}{{$jsTimeStamp}}"></script>
        @endforeach
        @endif

    </body>
</html>