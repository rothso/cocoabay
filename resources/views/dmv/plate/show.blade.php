@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            @include('partials.messages')
            <p>Viewing plate <pre>{{ $plate->tag }}</pre>.</p>
        </div>
    </div>
</div>
@endsection