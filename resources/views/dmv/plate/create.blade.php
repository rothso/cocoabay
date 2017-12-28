@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        {{ BootForm::open(['model' => null, 'store' => 'plate.store', 'update' => 'plate.update']) }}
            {!! BootForm::radios('style_id', 'Style', $styles, null, null, ['required']) !!}
            {!! BootForm::text('make', null, null, ['required']) !!}
            {!! BootForm::text('model', null, null, ['required']) !!}
            {!! BootForm::text('class', null, null, ['required']) !!}
            {!! BootForm::text('color', null, null, ['required']) !!}
            {!! BootForm::number('year', null, null, ['required']) !!}
            {!! BootForm::submit('Register Vehicle') !!}
        {{ BootForm::close() }}
    </div>
</div>
@endsection