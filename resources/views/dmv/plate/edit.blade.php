@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h1>Editing plate <b>{{ $plate->tag }}</b></h1>
        <p>Vehicle: {{ $plate->make }} {{ $plate->model }}</p>
        @include('dmv.plate._form', ['submit' => 'Update Vehicle'])
    </div>
</div>
@endsection