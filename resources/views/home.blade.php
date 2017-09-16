@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <h1>Psst...</h1>
        <p>You can now create a <b>Driver's License</b> with no wait.</p>
        <a class="btn btn-primary btn-large" href="{{ route('dmv') }}">Check it out!</a>
    </div>

    {{--<div class="row">--}}
        {{--<div class="col-md-8 col-md-offset-2">--}}
            {{--<div class="panel panel-default">--}}
                {{--<div class="panel-heading">Dashboard</div>--}}

                {{--<div class="panel-body">--}}
                    {{--You are logged in!--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
</div>
@endsection
