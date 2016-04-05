@extends('app')

@section('content')
    
    <div class="col-xs-12">
        <h1>Records</h1>
        @foreach ($albums as $key => $album)
            @if (($key + 1) % 4 == 0)
                <div class="row">
            @endif

            <div class="col-xs-3">
                <img src="{{ $album->thumb }}"/>
                <p>{{ $album->title }}</p>
                <p><a href="/artists/{{ $album->artist->id }}/albums/{{ $album->id }}">View</a></p>
            </div>

            @if (($key + 1) % 4 == 0)
                </div>
            @endif
        @endforeach
    </div>

    <div class="col-xs-12">
        {!! $albums->render() !!}
    </div>
@stop