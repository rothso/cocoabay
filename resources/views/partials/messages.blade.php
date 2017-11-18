@foreach (['danger', 'warning', 'success', 'info'] as $msg)
    @if(session($msg))
        <p class="alert alert-{{ $msg }}">{{ session($msg) }}<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
    @endif
@endforeach