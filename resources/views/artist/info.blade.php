@extends('app')

@section('content')

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="col-xs-6"><h2>{{ $data['artist']->name }}</h2></div>
                    <div class="col-xs-6">
                        {!! Form::open(['method' => 'DELETE', 'url' => '/artists/' . $data['artist']->id . '/delete']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-default']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <img class="img-rounded" src="{{ $data['artist']->image }}" style="width:100%">
            </div>

            <div class="col-xs-6">
                @if (!empty($data['artist']->bio))
                    <div class="col-xs-2"><p><b>Bio</b></p></div>
                    <div class="col-xs-10"><p>{{ $data['artist']->bio }}</p></div>
                @endif
                @if (!empty($data['artist']->members))
                    <div class="col-xs-2"><p><b>Members</b></p></div>
                    <div class="col-xs-10"><p>{{ $data['artist']->members }}</p></div>
                @endif
                <div class="col-xs-2"><p><b>Website</b></p></div>
                <div class="col-xs-10"><a href="{{ $data['artist']->website }}">{{ $data['artist']->website }}</a></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3>Records in your collection</h3>
                @if (count($data['artist']->albums) == 0)
                    <p>Currently you don't have any records from this artist. Search and add a record to your collection. <a href="/artists/{{ $data['artist']->discogsId }}/albums">Click here</a></p>
                @else
                    @foreach ($data['artist']->albums as $album)
                        <p>{{ $album->title }} <a href="/artists/{{ $data['artist']->id }}/albums/{{ $album->id }}">View</a></p>
                    @endforeach
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3>Similar Artists</h3>
                @foreach ($data['similar']->similarartists->artist as $artist)
                    <p>{{ $artist->name }}</p>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3>Upcoming Events</h3>
                @foreach ($data['events']->results->event as $event)
                    <p>
                        {{ $event->displayName }}<br>
                        {{ $event->start->date }}, {{ $event->start->time }}<br>
                        {{ $event->location->city }}
                    </p>
                @endforeach
            </div>
        </div>
    </div>

@stop