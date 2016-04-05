@extends('app')

@section('content')

    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-12">
                <h2>{{ $data->title }} ({{ $data->year }})</h2>
                <img class="img-rounded" src="{{ $data->image }}" style="width:20%">
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <h3>Track Listing</h3>
                @foreach ($data->tracks as $track)
                    <p>{{ $track->title }}</p>
                @endforeach
            </div>
        </div>
    </div>

@stop