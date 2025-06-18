@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <a id="back" href="#" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 mb-4">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <div class="mb-4">
                @foreach ($tags as $tag)
                    <a href="{{ route('tag', $tag->id) }}"
                        style="background-color:rgb(252, 252, 252); color:#94a7fc; text-decoration:none;border-radius:25px; margin-right:5px;"
                        class="px-3 py-1 rounded-full text-sm font-medium">
                        #{{ $tag->tag_name }}
                    </a>
                @endforeach
            </div>
            @if (isset($posts))
                <div class="row">
                    @foreach ($posts as $p)
                        @include('partials.blog', ['post' => $p])
                    @endforeach
                </div>
                <div class="pagination mt-3">
                    {{ $posts->links() }}
                </div>
            @else
                <p>Chưa có bài viết nào! </p>
            @endif
        </div>
    </div>
    <script>
        document.getElementById('back').addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = '/home';
        });
    </script>
@endsection