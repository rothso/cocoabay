<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style type="text/css">
        .navbar {
            margin-bottom: 0;
        }

        .panel-heading {
            font-weight: bold;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="hero" style="background: #52939c">
        <nav class="navbar navbar-inverse navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                        @else
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu" style="border-radius: 4px">
                                    <li>
                                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            Logout
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            {{ csrf_field() }}
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </nav>

        <div class="jumbotron" style="background: transparent;">
            <div class="container">
                <h1>DMV <small>Self Service</small></h1>
                <p>Welcome to the Department of Motor Vehicles.</p>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                    @if(Session::has('alert-' . $msg))
                        <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading">
                        Driver's License
                        @if(!is_null($license))
                            @if(!$license->is_expired)
                                <span class="label label-success pull-right">Active</span>
                            @else
                                <span class="label label-danger pull-right">Expired {{ $license->expiry->diffForHumans() }}</span>
                            @endif
                        @endif
                    </div>
                    <div class="panel-body">
                        @if(is_null($license))
                            <p>You don't have a license yet. As a Resident, you may create one now for free.</p>
                            <a class="btn btn-primary" href="{{ route('license') }}" role="button">Create a License</a>
                        @else
                            <p>You have a license!@if(!$license->is_expired) It will expire {{ $license->expiry->diffForHumans() }}.@endif</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel">
                    <div class="panel-heading">Vehicle Registration</div>
                    <div class="panel-body">
                        <p class="lead text-center" style="opacity: 0.5">Coming soon!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
