@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Register</div>
                    <div class="panel-body">
                        At Cocoa Bay, we provide all residents with an in-world <b>Password
                            Set HUD</b> for registering an account. This tool allows you to connect
                        and verify your SL identity with the portal without disclosing your SL
                        password. Instead, you can provide a password of your choosing to be used to
                        log in to Cocoa Bay.

                        <h3>Creating an account</h3>
                        A new account will be registered under your SL username the first time you set a password
                        with the HUD. Afterwards, setting a password will update the password of your existing
                        account. To use the HUD:

                        <ol style="margin-top: 11px">
                            <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</li>
                            <li>Suspendisse rhoncus arcu ac nunc tincidunt, vitae tristique massa
                                ultricies.
                            </li>
                            <li>Etiam eleifend nibh suscipit, rutrum urna a, euismod nibh.</li>
                            <li>Mauris id felis tempus, finibus massa ac, ultrices nibh.</li>
                        </ol>

                        You may now <a href="{{ route('login') }}">log in</a> with your SL username
                        and your new password.


                        <div class="bs-callout bs-callout-info">
                            <h4>Forgot your password?</h4>
                            No problem! Just set a new one using the same steps above and your
                            existing account will be updated.
                        </div>

                        <div class="bs-callout bs-callout-danger">
                            <h4>Problems?</h4>
                            Please contact support if you receive any errors or are experiencing
                            problems while setting your password or logging in.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
