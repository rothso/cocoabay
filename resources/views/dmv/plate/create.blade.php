@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach

        <form class="form-horizontal" method="POST" action="{{ route('plate.store') }}">
            {{ csrf_field() }}

            {{-- License plate styles --}}
            <div class="form-group">
                <label class="col-md-4 control-label">Style</label>
                <div class="col-md-6">
                    @foreach($styles as $style)
                    <label class="radio-inline">
                        <input type="radio" name="style_id" value="{{ $style->id }}" required> {{ $style-> name }}
                        <img src="{{ asset($style->image) }}">
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Make --}}
            <div class="form-group">
                <label for="make" class="col-md-4 control-label">Make</label>
                <div class="col-md-6">
                    <input id="make" class="form-control" name="make" required>
                </div>
            </div>

            {{-- Model --}}
            <div class="form-group">
                <label for="model" class="col-md-4 control-label">Model</label>
                <div class="col-md-6">
                    <input id="model" class="form-control" name="model" required>
                </div>
            </div>

            {{-- Class --}}
            <div class="form-group">
                <label for="class" class="col-md-4 control-label">Class</label>
                <div class="col-md-6">
                    <input id="class" class="form-control" name="class" required>
                </div>
            </div>
            
            {{-- Model --}}
            <div class="form-group">
                <label for="color" class="col-md-4 control-label">Model</label>
                <div class="col-md-6">
                    <input id="color" class="form-control" name="color" required>
                </div>
            </div>

            {{-- Year --}}
            <div class="form-group">
                <label for="year" class="col-md-4 control-label">Year</label>
                <div class="col-md-6">
                    <input id="year" type="number" class="form-control" name="year" required>
                </div>
            </div>

            <div class="col-md-6 col-md-push-4">
                <button type="submit" class="btn btn-primary">Register Vehicle</button>
            </div>
        </form>
    </div>
</div>
@endsection