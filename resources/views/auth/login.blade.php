@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="panel panel-default">
                <div class="panel-heading">Login</div>
                <div class="panel-body" style="padding: 30px">
                    <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">SL Username</label>

                            <div class="col-md-6">
                                <input id="username" class="form-control" name="username" value="{{ old('username') }}" required autofocus>

                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" aria-describedby="password-help" required>
                                <span id="password-help" class="help-block">
                                    Note: This is not your SL password, but a password you register from your <b>Set Password HUD</b> in-world.
                                </span>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-3">
                                <button class="btn btn-primary btn-block">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>

                    <div style="text-align: center">
                        <a class="btn btn-link" data-toggle="collapse" href="#forgot" aria-expanded="false" aria-controls="forgot">
                            Forgot your password?
                        </a>
                    </div>

                    <div class="collapse" id="forgot">
                        <div class="well" style="margin-bottom: 0">
                            You can set a new password in-world using the <b>Password Set HUD</b>. <a href=" {{ route('register') }}">Read more</a> about how to use the HUD.
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="panel" style="padding: 30px; height: calc(100% - 22px); background-image: url('http://cocoabay.net/wp-content/uploads/2017/04/MaintenanceMode2.png'); background-size: cover;">
                    <div class="row" style="margin-top: 50px; margin-bottom: 25px;">
                        <div class="col-md-8 col-md-offset-2 text-center">
                            <img src="{{ URL::asset('/img/logo-cropped.png') }}" class="responsive-image center-block" style="max-width: 100%; margin-bottom: 28px">
                            <span style="color: #ffffff; text-shadow: 0 0 10px #000000, 0 0 3px #000000;">
                                Having an account lets you access the DMV self-service, apply for positions, and more.
                            </span>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 50px">
                        <div class="col-md-6 col-md-offset-3">
                            <a class="btn btn-default btn-block" href="{{ route('register') }}">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
