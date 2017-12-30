{{ BootForm::open(['model' => $plate, 'store' => 'plate.store', 'update' => 'plate.update']) }}
    {!! BootForm::radios('style_id', 'Style', $styles, null, null, ['required']) !!}
    {!! BootForm::text('make', null, null, ['required']) !!}
    {!! BootForm::text('model', null, null, ['required']) !!}
    {!! BootForm::text('class', null, null, ['required']) !!}
    {!! BootForm::text('color', null, null, ['required']) !!}
    {!! BootForm::number('year', null, null, ['required']) !!}
    {!! BootForm::submit($submit) !!}
{{ BootForm::close() }}