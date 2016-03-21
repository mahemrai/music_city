@extends('app')

@section('content')

    <div class="col-xs-12">
        <h1>Artists</h1>
        @foreach ($list as $key => $item)
            <h3>{{ strtoupper($key) }}</h3>
            @foreach ($item as $artist)
                <p><a href="artists/{{ $artist['id'] }}">{{ $artist['name'] }}</a></p>
            @endforeach
        @endforeach
    </div>

@stop