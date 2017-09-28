@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Create</div>
                    <div class="panel-body">
                        <form class="form-horizontal" method="POST" action="{{ route('license') }}">
                            {{ csrf_field() }}

                            {{-- Name (static) --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <p class="form-control-static">{{ Auth::user()->name }}</p>
                                </div>
                            </div>

                            {{-- UUID (static) --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label">UUID</label>

                                <div class="col-md-6">
                                    <p class="form-control-static">{{ Auth::user()->uuid }}</p>
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                                <label class="col-md-4 control-label">Sex</label>

                                <div class="col-md-6">
                                    <label for="gender-male" class="radio-inline">
                                        <input type="radio" name="gender" id="gender-male" value="MALE" autofocus required @if(old('gender') == 'MALE') checked @endif> Male
                                    </label>
                                    <label for="gender-female" class="radio-inline">
                                        <input type="radio" name="gender" id="gender-female" value="FEMALE" required @if(old('gender') == 'FEMALE') checked @endif> Female
                                    </label>
                                </div>
                            </div>

                            {{-- Date of birth --}}
                            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                <label for="dob" class="col-md-4 control-label">Date of Birth</label>

                                <div class="col-md-6">
                                    <input id="dob" type="date" class="form-control" name="dob" value="{{ old('dob') }}" required>

                                    @if ($errors->has('dob'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('dob') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Height --}}
                            <div class="form-group">
                                <div {!! $errors->has('height_ft') || $errors->has('height_in') ? 'class="has-error"' : '' !!}>
                                    <label class="col-md-4 control-label">Height</label>
                                </div>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-6 {{ $errors->has('height_ft') ? ' has-error' : '' }}">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="height_ft" name="height_ft" value="{{ old('height_ft') }}" placeholder="Feet" required>
                                                <div class="input-group-addon">ft</div>
                                            </div>


                                            @if ($errors->has('height_ft'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('height_ft') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6 {{ $errors->has('height_in') ? ' has-error' : '' }}">
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="height_in" name="height_in" value="{{ old('height_in') }}" placeholder="Inches" required>
                                                <div class="input-group-addon">in</div>
                                            </div>

                                            @if ($errors->has('height_in'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('height_in') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Weight --}}
                            <div class="form-group{{ $errors->has('weight_lb') ? ' has-error' : '' }}">
                                <label for="weight" class="col-md-4 control-label">Weight</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="weight" type="number" class="form-control" name="weight_lb" value="{{ old('weight_lb') }}" placeholder="Weight" required>
                                        <div class="input-group-addon">lb</div>
                                    </div>

                                    @if ($errors->has('weight_lb'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('weight_lb') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Eye color --}}
                            <div class="form-group{{ $errors->has('eye_color_id') ? ' has-error' : ''}}">
                                <label for="eye_color" class="col-md-4 control-label">Eye Color</label>

                                <div class="col-md-6">
                                    <select id="eye_color" class="form-control" name="eye_color_id">
                                        @foreach($eyeColors as $key => $color)
                                            <option value="{{ $key }}">{{ $color }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('eye_color_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('eye_color_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{--Hair Color--}}
                            <div class="form-group{{ $errors->has('hair_color_id') ? ' has-error' : ''}}">
                                <label for="hair_color" class="col-md-4 control-label">Hair Color</label>

                                <div class="col-md-6">
                                    <select id="hair_color" class="form-control" name="hair_color_id">
                                        @foreach($hairColors as $key => $color)
                                            <option value="{{ $key }}">{{ $color }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('hair_color_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('hair_color_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{--Address--}}
                            <div class="form-group{{ $errors->has('address') ? ' has-error' : '' }}">
                            <label for="address" class="col-md-4 control-label">Street Address</label>

                                <div class="col-md-6">
                                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address') }}" placeholder="Address" required>

                                    @if ($errors->has('address'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('address') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Sim --}}
                            <div class="form-group{{ $errors->has('sim') ? ' has-error' : '' }}">
                                <label for="sim" class="col-md-4 control-label">Sim</label>

                                <div class="col-md-6">
                                    <input id="sim" type="text" class="form-control" name="sim" value="{{ old('sim') }}" placeholder="Sim" required>

                                    @if ($errors->has('sim'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sim') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <div class="col-md-6 col-md-offset-4">
                                    <button type="submit" class="btn btn-primary">
                                        Create
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection