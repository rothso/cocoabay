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
            background:
                linear-gradient(-45deg,
                    rgba(16, 156, 133, 1) 0%,
                    rgba(16, 156, 133, 1) 25%,
                    rgba(16, 156, 133, 0) 100%
                ),
                linear-gradient(
                    rgba(16, 156, 133, 0.85),
                    rgba(16, 156, 133, 0.85)
                ),
                url('{{ asset('img/joey-kyber-121699.jpg') }}') no-repeat 0 -415px / cover;
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
