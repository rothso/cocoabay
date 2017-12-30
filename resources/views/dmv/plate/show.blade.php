@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('partials.messages')
        <h1>Viewing plate <b>{{ $plate->tag }}</b>.</h1>
        <p>Vehicle: {{ $plate->make }} {{ $plate->model }}</p>
    </div>
</div>
@endsection