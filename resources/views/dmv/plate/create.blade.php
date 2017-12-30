@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <h1>Creating new plate</h1>
        @include('dmv.plate._form', ['submit' => 'Register Vehicle'])
    </div>
</div>
@endsection