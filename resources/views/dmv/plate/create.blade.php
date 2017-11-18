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
                    <label class="radio-inline">
                        <input type="radio" name="style_id" value="1"> NYC
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="style_id" value="2"> State
                    </label>
                    <label class="radio-inline">
                        <input type="radio" name="style_id" value="3"> Police
                    </label>
                </div>
            </div>

            {{-- Make --}}
            <div class="form-group">
                <label for="make" class="col-md-4 control-label">Make</label>
                <div class="col-md-6">
                    <input id="make" class="form-control" name="make">
                </div>
            </div>

            {{-- Model --}}
            <div class="form-group">
                <label for="model" class="col-md-4 control-label">Model</label>
                <div class="col-md-6">
                    <input id="model" class="form-control" name="model">
                </div>
            </div>

            {{-- Class --}}
            <div class="form-group">
                <label for="class" class="col-md-4 control-label">Class</label>
                <div class="col-md-6">
                    <input id="class" class="form-control" name="class">
                </div>
            </div>
            
            {{-- Model --}}
            <div class="form-group">
                <label for="color" class="col-md-4 control-label">Model</label>
                <div class="col-md-6">
                    <input id="color" class="form-control" name="color">
                </div>
            </div>

            {{-- Year --}}
            <div class="form-group">
                <label for="year" class="col-md-4 control-label">Year</label>
                <div class="col-md-6">
                    <input id="year" type="number" class="form-control" name="year">
                </div>
            </div>

            <div class="col-md-6 col-md-push-4">
                <button type="submit" class="btn btn-primary">Register Vehicle</button>
            </div>
        </form>
    </div>
</div>
@endsection