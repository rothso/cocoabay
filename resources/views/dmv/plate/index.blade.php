@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @include('partials.messages')
        All license plates: <br>
        @foreach($plates as $plate)
            {{-- TODO: link to plate --}}
            {{ $plate->tag }}: {{ $plate->make }} {{ $plate->model }}
        @endforeach
    </div>
</div>
@endsection