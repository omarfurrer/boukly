@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card-columns">
            @foreach ($bookmarks as $bookmark)
            <div class="card">
                <a
                    href="{{$bookmark->url}}"
                    target="_blank"
                >
                    <img
                        src="{{$bookmark->image == null ? 'https://picsum.photos/300' : $bookmark->image}}"
                        class="card-img-top"
                        alt="..."
                    >
                </a>
                <div class="card-body{{ $bookmark->is_dead ? ' text-danger' : '' }}">
                    <h5 class="card-title">{{$bookmark->title == null ? 'NO TITLE' : $bookmark->title}}</h5>
                    <p class="card-text">{{$bookmark->description == null ? 'NO TITLE' : $bookmark->description}}</p>
                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection