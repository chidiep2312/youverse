@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="text-primary mb-3">Các bài viết trong <strong>{{ $folder->name }}</strong> -
                            {{ $folder->user->name }}
                        </h3>
                        <div class="row">
                            <div class="col-12 ">

                                @if (isset($posts))
                                    <div class="row">
                                        @foreach ($posts as $post)
                                            @include('partials.blog', ['post' => $post])
                                        @endforeach
                                    </div>

                                    <div class="pagination mt-3">
                                        {{ $posts->links() }}
                                    </div>
                                @else
                                    <h3>Không có bài viết!</h3>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection