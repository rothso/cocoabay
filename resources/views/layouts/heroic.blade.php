<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.head')

    <style type="text/css">
        .navbar {
            margin-bottom: 0;
        }

        .panel-heading {
            font-weight: bold;
        }

        .label-dark {
            background: #333;
        }

        .hero {
            background: #109c85;
            padding-top: 10px;
            margin-bottom: 50px;
            box-shadow: inset 0 -7px 9px -7px rgba(0, 0, 0, 0.15);
        }

        .hero .jumbotron {
            background: transparent;
        }
    </style>
</head>
<body>
<div id="app">
    <div class="hero">
        @include('includes.nav', ['navbar' => 'inverse'])

        <div class="jumbotron">
            @yield('hero')
        </div>
    </div>

    @yield('content')
</div>

@include('includes.footer')
</body>
</html>
