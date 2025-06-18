@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/drafted-post.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="post-detail">
                <div class="post-detail-inner">
                    <h1 class="post-title" style="color:#367517;">{{$drafted->title}}</h1>
                    <div class="dropdown" style="float:right;">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                            style="background-color:transparent;color:grey; border: none;" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            Options
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="">Edit</a>
                            <a class="dropdown-item" href="#" id="delete-btn">Delete</a>
                        </div>
                    </div>
                    <div class="post-meta">
                        <label class="post-tag" style="color:#50A625;">Tag: </label><span>{{$drafted->tag->tag_name}}
                        </span>
                        <label class="post-date" style="color:#50A625;">Created
                            at:</label><span>{{$drafted->created_at}}</span>
                        <label class="post-author" style="color:#50A625;">Người
                            viết:</label><span>{{$drafted->user->name}}</span>
                    </div>
                    <div class="post-image">
                        <img style="width:100%;height:400px;object-fit:contain;" alt="Hình ảnh bài đăng">
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>

    </script>
@endsection