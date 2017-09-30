@extends('layouts.heroic')

@section('hero')
<div class="container">
    <h1>DMV <small>Self Service</small></h1>
    <p>Licensing and Registration Division</p>
</div>
@endsection

@section('content')
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
    {{-- TODO turn into a partial for heading/body & authed/not authed --}}
    <div class="row">
        <div class="col-lg-6">
            <div class="panel">
                <div class="panel-heading">
                    Driver's License
                    @if(!is_null($license))
                        @if(!$license->is_expired)
                            <div class="pull-right">
                                <span class="label label-dark">{{ $license->formatted_number }}</span>
                                <span class="label label-success">Active</span>
                            </div>
                        @else
                            <span class="label label-danger pull-right">Expired {{ $license->expires_at->diffForHumans() }}</span>
                        @endif
                    @endif
                </div>
                <div class="panel-body">
                    @if(is_null($license))
                        <p>You don't have a license yet. As a Resident, you may create one now for free.</p>
                        <a class="btn btn-primary" href="{{ route('license') }}" role="button">Create a License</a>
                    @else
                        <p>You have a license!@if(!$license->is_expired) It will expire {{ $license->expires_at->diffForHumans() }}, on {{ $license->expires_at->format('F jS') }}.@endif</p>
                        <ul>
                            <li><a href="{{ asset('storage/' . $license->image) }}">View your license</a></li>
                            <li><a href="{{ route('license') }}">Edit your license</a></li>
                            <li><span style="color: #999">Renew your license</span></li>
                        </ul>
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
@endsection