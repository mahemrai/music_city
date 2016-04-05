@extends('app')

@section('content')

    <div class="col-xs-12">
        <div>
            <p>Search for an artist</p>

            <form class="form-inline" method="post" action="/results">
                <div class="form-group">
                    <input class="form-control" name="artist" type="text">
                    <button class="btn btn-primary">Find</button>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Recently Added Artists</h2>
                @if (count($data['recentArtists']) == 0)
                    <p>Currently you have no artists in your collection.</p>
                @else
                    @foreach ($data['recentArtists'] as $artist)
                        <div class="col-xs-3">
                            <img class="img-circle" src="{{ $artist->thumb }}">
                            <p>{{ $artist->name }}</p>
                            <p><a href="/artists/{{ $artist->id }}">View</a></p>
                            <p><a href="/artists/{{ $artist->discogsId }}/albums">Find Albums</a></p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h2>Recently Added Records</h2>
                @if (count($data['recentRecords']) == 0)
                    <p>Currently you have no records in your collection.</p>
                @else
                    @foreach ($data['recentRecords'] as $record)
                        <div class="col-xs-3">
                            <img class="img-circle" src="{{ $record->thumb }}">
                            <p>{{ $record->title }}</p>
                            <p><a href="/artists/{{ $record->artist_id }}/albums/{{ $record->id }}">View</a></p>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@stop