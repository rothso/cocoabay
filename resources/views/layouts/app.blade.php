<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @include('includes.head')
</head>
<body>
    <div id="app">
        @include('includes.nav')

        @yield('content')
    </div>

    @include('includes.footer')
</body>
</html>
