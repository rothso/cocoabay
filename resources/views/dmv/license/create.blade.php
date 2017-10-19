@extends('layouts.heroic')

<style type="text/css">
    .hero {
        margin-bottom: -75px !important;
        padding-bottom: 25px !important;
    }
    .hero .breadcrumb {
        background: transparent;
        padding-left: 0;
        padding-right: 0;
    }
    .hero .breadcrumb li:not(.active) {
        opacity: 0.5;
    }
    .panel {
        box-shadow: 0 0 10px rgba(0,0,0,0.15) !important;
    }
</style>

@section('hero')
<div class="container">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            @if($license->exists)
                <h2 class="text-center">Edit License</h2>
                <p class="text-center" style="opacity: 0.5">Need to update your license? No problem!</p>
            @else
                <h2 class="text-center">Create License</h2>
                <p class="text-center" style="opacity: 0.5">Want to drive? We'll mint you a fresh license.</p>
            @endif
        </div>
        <div class="col-md-8 col-md-offset-2 hidden">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('dmv') }}">DMV</a></li>
                <li class="active">Edit License</li>
            </ol>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <form class="form-horizontal" method="POST" action="{{ route('license') }}" enctype="multipart/form-data">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a class="btn btn-default" href="{{ route('dmv') }}">
                                <span class="glyphicon glyphicon-menu-left"></span>
                                {{ !$license->exists ? 'Go Back' : 'Cancel' }}
                            </a>
                            <button type="submit" class="btn btn-primary pull-right">
                                {{ !$license->exists ? 'Create License' : 'Save Changes' }}
                            </button>
                        </div>
                        <div class="panel-body">
                            {{ csrf_field() }}
                            {{ $license->exists ? method_field('PATCH') : '' }}

                            {{-- Name (static) --}}
                            <div class="form-group">
                                <label class="col-md-4 control-label">Name</label>

                                <div class="col-md-6">
                                    <p class="form-control-static">{{ $user->name }}</p>
                                </div>
                            </div>

                            {{-- Photo --}}
                            <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                                <label class="col-md-4 control-label">Photo<br><span style="color: #999; font-size: 12px">Face Picture Only</span></label>

                                <div class="col-md-6">
                                    {{-- TODO: extract into Vue component --}}
                                    <div class="thumbnail" style="margin-bottom: 0">
                                        <img id="photo-preview" class="hidden img-responsive" src="{{ is_null($license->photo) ? '' : Storage::url($license->photo) }}" style="width: 200px; height: 200px; object-fit: cover; margin-bottom: 5px">
                                        <label id="photo-choose" for="photo" class="btn btn-block btn-default btn-file">Choose new photo</label>
                                        <button id="photo-cancel" type="button" class="btn btn-block btn-danger hidden">Cancel selection</button>
                                    </div>
                                    <input class="hidden" type="file" name="photo" id="photo" accept="image/*">

                                    @if ($errors->has('photo'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('photo') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Gender --}}
                            <div class="form-group {{ $errors->has('gender') ? 'has-error' : '' }}">
                                <label class="col-md-4 control-label">Sex</label>

                                <div class="col-md-6">
                                    <label for="gender-male" class="radio-inline">
                                        <input type="radio" name="gender" id="gender-male" value="MALE" required @if(old('gender', $license->gender) == 'MALE') checked @endif> Male
                                    </label>
                                    <label for="gender-female" class="radio-inline">
                                        <input type="radio" name="gender" id="gender-female" value="FEMALE" required @if(old('gender', $license->gender) == 'FEMALE') checked @endif> Female
                                    </label>

                                    @if ($errors->has('gender'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Date of birth --}}
                            <div class="form-group{{ $errors->has('dob') ? ' has-error' : '' }}">
                                <label for="dob" class="col-md-4 control-label">Date of Birth (<abbr title="Roleplay">RP</abbr>)</label>

                                <div class="col-md-6">
                                    <div class="input-group date" id="datepicker">
                                        <input id="dob" type="text" class="form-control" name="dob" value="{{ old('dob', optional($license->dob)->format('Y-m-d')) }}" required>
                                        <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
                                    </div>

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
                                                <input type="number" class="form-control" id="height_ft" name="height_ft" value="{{ old('height_ft', !is_null($license) ? floor($license->height_in / 12) : null) }}" placeholder="Feet" required>
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
                                                <input type="number" class="form-control" id="height_in" name="height_in" value="{{ old('height_in', !is_null($license) ? $license->height_in % 12 : null) }}" placeholder="Inches" required>
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
                                        <input id="weight" type="number" class="form-control" name="weight_lb" value="{{ old('weight_lb', $license->weight_lb) }}" placeholder="Weight" required>
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
                                            <option value="{{ $key }}" @if($license->eye_color_id == $key)selected @endif>{{ $color }}</option>
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
                                            <option value="{{ $key }}" @if($license->hair_color_id == $key)selected @endif>{{ $color }}</option>
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
                                    <input id="address" type="text" class="form-control" name="address" value="{{ old('address', $license->address) }}" placeholder="Address" required>

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
                                    <input id="sim" type="text" class="form-control" name="sim" value="{{ old('sim', $license->sim) }}" placeholder="Sim" required>

                                    @if ($errors->has('sim'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sim') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    $(function () {
        $('#datepicker').datetimepicker({
            viewMode: 'years',
            format: 'YYYY-MM-DD', // format sent to the server
            extraFormats: [ // other accepted user inputs
                'MM/DD/YYYY',
                'MM-DD-YYYY'
            ]
        });

        // Dirty image upload magic. TODO: refactor into Vue component
        const originalPhotoSrc = $("#photo-preview").attr('src');

        if (originalPhotoSrc !== '') {
            $("#photo-preview").removeClass('hidden');
        }

        $("#photo").change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#photo-preview').removeClass('hidden');
                    $('#photo-preview').attr('src', e.target.result);
                    $('#photo-choose').addClass('hidden');
                    $('#photo-cancel').removeClass('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            }
        });

        function resetFormElement(e) {
            e.wrap('<form>').closest('form').get(0).reset();
            e.unwrap();

            // Prevent form submission
            e.stopPropagation();
            e.preventDefault();
        }

        $("#photo-cancel").click(function() {
            if (!originalPhotoSrc) {
                $("#photo-preview").addClass('hidden');
            }

            $(this).addClass('hidden');
            $("#photo-choose").removeClass('hidden');
            $("#photo-preview").attr('src', originalPhotoSrc);

            // clear the underlying input field
            resetFormElement($("#photo")); // MUST be called last for some reason
        })
    });
</script>
@endpush