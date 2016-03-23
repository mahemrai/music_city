@extends('app')

@section('content')

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <div class="col-xs-6">
                    <div class="col-xs-6"><h2>{{ $data->name }}</h2></div>
                    <div class="col-xs-6">
                        {!! Form::open(['method' => 'DELETE', 'url' => '/artists/' . $data->id . '/delete']) !!}
                            {!! Form::submit('Delete', ['class' => 'btn btn-default']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <img class="img-rounded" src="{{ $data->image }}" style="width:100%">
            </div>

            <div class="col-xs-6">
                @if (!empty($data->bio))
                    <div class="col-xs-2"><p><b>Bio</b></p></div>
                    <div class="col-xs-10"><p>{{ $data->bio }}</p></div>
                @endif
                @if (!empty($data->members))
                    <div class="col-xs-2"><p><b>Members</b></p></div>
                    <div class="col-xs-10"><p>{{ $data->members }}</p></div>
                @endif
                <div class="col-xs-2"><p><b>Website</b></p></div>
                <div class="col-xs-10"><a href="{{ $data->website }}">{{ $data->website }}</a></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3>Records in your collection</h3>
                @if (count($data->albums) == 0)
                    <p>Currently you don't have any records from this artist. Search and add a record to your collection. <a href="/artists/{{ $data->discogsId }}/albums">Click here</a></p>
                @else
                    @foreach ($data->albums as $album)
                        <p>{{ $album->title }} <a href="/artists/{{ $data->id }}/albums/{{ $album->id }}">View</a></p>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

@stop