@extends('app')

@section('content')

    <div class="col-xs-12">
        <h1>Results</h1>
        <table class="table">
        <tbody>
            @if (is_array($data) && isset($data['album']->releases))
                @foreach ($data['album']->releases as $item)
                    @if ($item->type == 'master' && $item->role == 'Main')
                        <tr>
                            <td><img src="{{ $item->thumb }}"></td>
                            <td>{{ $item->title }}</td>
                            <td>
                                <form method="post" action="/artists/{{ $data['artist'] }}/albums/{{ $item->id }}">
                                    <button class="btn btn-default">Add</button>
                                </form>
                            </td>
                        </tr>
                    @endif
                @endforeach
            @else
                @foreach ($data->results as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>
                            <form method="post" action="artists/{{ $item->id }}">
                                <button class="btn btn-default">Add</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </div>

@stop