@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <a id="back" href="#" class="btn btn-outline-secondary">
                    &laquo; Quay lại
                </a>
                @if ($topic->is_pinned != true)
                    <a href="#" id="pin" class="btn btn-outline-primary">
                        📌 Ghim
                    </a>
                @else
                    <a href="#" id="unpin" class="btn btn-outline-danger">
                        📌 Bỏ ghim
                    </a>
                @endif
            </div>

            <div class="short-post-list">
                <div class="short-post-card">
                    <div class="short-post-header">
                        <div class="user-info">
                            <img src="{{ asset('storage/' . $topic->user->avatar) }}" class="rounded-circle shadow-sm"
                                width="48" height="48" style="object-fit: cover; border: 2px solid #fff;">
                            <strong>{{ $topic->user->name }}</strong>
                            <small>{{ $topic->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    <p class="short-post-excerpt">
                        {!! $topic->content !!}
                    </p>
                    <div class="short-post-footer pt-4 mt-4 border-top">
                        <div id="comment-list-{{ $topic->id }}">
                            @if ($topic->comments->count() > 0)
                                @foreach ($topic->comments as $c)
                                    <div class="comment-box d-flex mb-4">
                                        <img src="{{ asset('storage/' . $c->user->avatar) }}" class="rounded-circle me-3"
                                            alt="avatar" style="width: 50px; height: 50px; object-fit: cover;">

                                        <div class="flex-grow-1 bg-light p-3 rounded-3 shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <strong class="text-dark">{{ $c->user->name }}</strong>
                                                <small class="text-muted d-flex align-items-center">
                                                    {{ $c->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                            <div>
                                                {{ $c->content }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>
    <script>
        document.getElementById('back').addEventListener('click', function (e) {
            e.preventDefault();
            window.history.back();
        });
        document.addEventListener('DOMContentLoaded', function () {
            const pinBtn = document.getElementById('pin');
            const unpinBtn = document.getElementById('unpin');
            const topic = {{ $topic->id }};
            const auth_token = localStorage.getItem('auth_token');
            if (pinBtn) {
                document.getElementById('pin').addEventListener('click', function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: `/admin/thread/topic/pin/${topic}`,
                        method: "GET",

                        success: function (response) {
                            if (response.success == true) {
                                alert("Đã ghim");
                                location.reload();
                            } else {
                                alert("Có lỗi xảy ra.");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Lỗi:", error);
                        }
                    });

                })
            }
            if (unpinBtn) {
                document.getElementById('unpin').addEventListener('click', function (e) {
                    e.preventDefault();
                    const topic = {{ $topic->id }};
                    const auth_token = localStorage.getItem('auth_token');

                    $.ajax({
                        url: `/admin/thread/topic/unpin/${topic}`,
                        method: "GET",

                        success: function (response) {
                            if (response.success == true) {
                                alert("Bỏ ghim thành công!");
                                location.reload();
                            } else {
                                alert("Có lỗi xảy ra.");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Lỗi:", error);
                        }
                    });

                })
            }

        });
    </script>
@endsection