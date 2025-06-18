@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/detail-post.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <a id="back" href="#" class="btn btn-link mb-4">
                &laquo; Quay lại
            </a>
            <div class="post-detail card p-4 shadow-sm rounded-4 bg-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h1 class="post-title text-primary fw-bold" style="font-size: 2.5rem;">
                        {{ $post->title }}
                    </h1>

                </div>

                <div class="post-meta d-flex flex-wrap gap-3 mb-4 text-secondary" style="font-size: 0.9rem;">
                    <span><strong class="text-primary">Thẻ:</strong> {{ $post->tag->tag_name }}</span>
                    <span><strong class="text-primary">Tạo lúc:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                    <span><strong class="text-primary">Người viết:</strong> {{ $post->user->name }}</span>
                    <span><i class="fa-solid fa-eye"></i> {{ $post->view_count }}</span>
                    <span><i class="fa-regular fa-heart"></i> {{ $likesCount }}</span>
                </div>

                <hr>

                <div class="post-content mb-5" style="line-height: 1.7; font-size: 1.1rem; color: #333;">
                    {!! $post->content !!}
                </div>

                @if($post->is_flag == true)
                    <button class="btn btn-sm btn-success approve-post">Duyệt</button>

                @endif
                <button class="btn btn-sm btn-danger delete-post">Xoá</button>
            </div>

        </div>

    </div>
    <script>
        document.getElementById('back').addEventListener('click', function (e) {
            e.preventDefault();
            window.history.back();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const post = {{ $post->id }};

            const deleteBtn = document.querySelector('.delete-post');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const auth_token = localStorage.getItem('auth_token');
                    if (confirm("Bạn có chắc muốn xóa bài viết này không?")) {
                        $.ajax({
                            url: `/api/admin/post/delete/${post}`,
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${auth_token}`
                            },
                            success: function (response) {
                                if (response.success) {
                                    alert(response.message);
                                    window.history.back();

                                } else {
                                    alert("Có lỗi xảy ra khi xóa.");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Lỗi:", error);
                            }
                        });
                    }
                });
            }

            const approveBtn = document.querySelector('.approve-post');
            if (approveBtn) {
                approveBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const auth_token = localStorage.getItem('auth_token');
                    if (confirm("Bạn có chắc muốn duyệt bài viết này không?")) {
                        $.ajax({
                            url: `/api/admin/post/approve-post/${post}`,
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${auth_token}`
                            },
                            success: function (response) {
                                if (response.success) {
                                    alert('Hoàn tất!');
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi duyệt.");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Lỗi:", error);
                            }
                        });
                    }
                });
            }
        });

    </script>
@endsection