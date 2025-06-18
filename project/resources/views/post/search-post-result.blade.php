@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            @if (isset($announcements))
                @foreach ($announcements as $a)
                    <div class="alert alert-warning d-flex align-items-center justify-content-between" role="alert">
                        <div>
                            <strong>📢 {{ $a->title }}:</strong>{{ $a->content }}
                        </div>
                    </div>
                @endforeach
            @endif

            <div class="tab-content" id="postTabsContent">
                <div class="tab-pane fade show active" id="blog" role="tabpanel">

                    @if ($posts)
                        <h3 class="font-weight-bold mb-4">Kết quả tìm kiếm</h3>
                        <div class="row">
                            @foreach ($posts as $post)
                                @include('partials.blog', ['post' => $post])
                            @endforeach
                            <div class="pagination mt-3">
                                {{ $posts->appends(request()->except('posts'))->links() }}
                            </div>
                        </div>
                    @else
                        <p>Không tìm thấy kết quả phù hợp</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection