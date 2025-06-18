@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/detail-post.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            @if ($post->group_id)
                <a href="{{ route('group.detail', ['user' => auth()->user(), 'group' => $post->group_id]) }}"
                    class="btn btn-link mb-4">
                    &laquo; Quay lại
                </a>
            @else
                <a id="back" href="#" class="btn btn-link mb-4">
                    &laquo; Quay lại
                </a>
            @endif

            <div class="post-detail card p-4 shadow-sm rounded-4 bg-white">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h1 class="post-title text-primary fw-bold" style="font-size: 2.5rem;">
                        {{ $post->title }}
                    </h1>
                    <input type="hidden" id="postId" value="{{ $post->id }}">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Lựa chọn
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton">
                            @if (auth()->id() == $post->user_id)
                                <a class="dropdown-item" href="{{ route('post.edit-post', $post->id) }}">Chỉnh sửa</a>
                                <a class="dropdown-item text-danger" href="#" id="delete-btn">Xóa</a>
                            @endif
                            <a class="dropdown-item" href="#" id="report" data-toggle="modal" data-target="#reportModal">Tố
                                cáo</a>
                        </div>
                    </div>
                </div>

                <div class="post-meta d-flex flex-wrap gap-3 mb-4 text-secondary" style="font-size: 0.9rem;">
                    <span><strong class="text-primary">Thẻ:</strong> {{ $post->tag->tag_name }}</span>
                    <span><strong class="text-primary">Tạo lúc:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                    <span><strong class="text-primary">Người viết:</strong> {{ $post->user->name }}</span>
                    <span id="views-count"><i class="fa-solid fa-eye"></i> {{ $post->view_count }}</span>
                    <span><i class="fa-regular fa-heart"></i><span
                            id="likes-count">{{ $post->likes->count() }}</span></span>
                </div>

                <hr>

                <div class="post-content" style="line-height: 1.7; font-style: italic;font-size: 1rem; color: #456b70;">
                    {!! $post->des !!}
                </div>
                <div class="post-content mb-5" style="line-height: 1.7; font-size: 1.1rem; color: #333;">
                    {!! $post->content !!}
                </div>

                <div class="post-likes mb-5">
                    <form id="like-action">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">Thích bài
                            viết</button>
                    </form>
                    <p id="like-alert" style="display:none;"></p>
                </div>

                <div class="post-comments">
                    <h3 class="mb-4 fw-bold">Khu bình luận</h3>
                    <div id="comment-list">
                        @include('partials.comments', ['comments' => $comments])
                    </div>


                    <form id="createComment" class="p-4 border rounded shadow-sm bg-light position-relative">
                        @csrf
                        <div class="mb-3">
                            <textarea id="comment_content" name="comment_content" rows="3"
                                class="form-control rounded-3 shadow-sm" placeholder="Viết bình luận..." required
                                style="resize: none;"></textarea>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-secondary px-4">Bình luận</button>
                        </div>
                    </form>
                </div>
            </div>


            <div class="suggested-posts mt-5">
                <h4 class="fw-bold mb-3 text-primary">Bài viết cùng tác giả</h4>
                <div class="d-flex align-items-center">
                    <button id="prev-btn" class="btn btn-outline-primary btn-sm me-2">
                        &laquo;
                    </button>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="row" id="slide-wrapper">
                            @foreach ($relatedPosts as $index => $post)
                                <div data-index="{{ floor($index / 3) }}" class=" slide-item">
                                    <div style="margin:5px;" class="blog-card-modern shadow-sm rounded overflow-hidden h-100">
                                        <div class="blog-thumbnail">
                                            <img src="{{ $post->thumbnail ?? asset('assets/images/default.png') }}"
                                                alt="{{ $post->title }}">
                                        </div>
                                        <div class="blog-body p-3 d-flex flex-column shadow-sm rounded border bg-white h-100">
                                            <div class="d-flex align-items-center mb-3">
                                                <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                                    class="rounded-circle me-3 shadow-sm"
                                                    style="width: 48px; height: 48px; object-fit: cover;">
                                                <div style="margin-left:5px">
                                                    <div class="fw-semibold">{{ $post->user->name }}</div>
                                                    <small class="text-muted">
                                                        <i class="bi bi-clock"></i>
                                                        {{ $post->created_at->format('d M Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                            <h5 class="fw-bold mb-2 text-dark">
                                                {!! Illuminate\Support\Str::limit(strip_tags($post->title), 20) !!}
                                            </h5>
                                            <p class="text-muted flex-grow-1" style="font-size: 15px;">
                                                {!! Illuminate\Support\Str::limit(strip_tags($post->des), 50) !!}
                                            </p>
                                            <div
                                                class="d-flex justify-content-between align-items-center small text-muted mt-auto">
                                                <div>
                                                    <i class="fa-solid fa-eye me-1"></i> {{ $post->view_count }}
                                                    &nbsp;&nbsp;
                                                    <i class="fa-regular fa-heart me-1"></i> {{ $post->likes->count() }}
                                                    &nbsp;&nbsp;
                                                    <i class="fa-regular fa-comment"></i> {{ $post->comments->count() }}
                                                </div>
                                                <a href="{{ route('post.detail', ['post' => $post->id]) }}"
                                                    class="btn btn-sm btn-outline-primary">Xem
                                                    chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>


                    <button id="next-btn" class="btn btn-outline-primary btn-sm ms-2">
                        &raquo;
                    </button>
                </div>
            </div>



        </div>
    </div>
    @include('modals.report')
    <script>
        const postId = document.getElementById('postId').value;
        document.addEventListener('DOMContentLoaded', function () {
            const items = document.querySelectorAll('.slide-item');
            const itemsPerPage = 3;
            const totalPages = Math.ceil(items.length / itemsPerPage);
            let currentPage = 0;

            function showPage(page) {
                items.forEach(item => {
                    item.style.display = item.dataset.index == page ? 'block' : 'none';
                });
            }

            document.getElementById('next-btn').addEventListener('click', function () {
                if (currentPage < totalPages - 1) currentPage++;
                showPage(currentPage);
            });

            document.getElementById('prev-btn').addEventListener('click', function () {
                if (currentPage > 0) currentPage--;
                showPage(currentPage);
            });

            showPage(currentPage);
        });
    </script>

    <script>
        $(document).on('click', '#loadMoreComments', function () {
            const button = $(this);
            const nextPage = button.data('next-page');

            const url = `/post/${postId}/comments?page=${nextPage}`;

            button.prop('disabled', true).text('Đang tải...');

            $.ajax({
                url: url,
                type: 'GET',
                success: function (data) {
                    const tempDiv = $('<div>').html(data);
                    const newComments = tempDiv.find('.comment-box');
                    $('#comment-list').append(newComments);


                    button.remove();
                    const newButton = tempDiv.find('#loadMoreComments');
                    if (newButton.length > 0) {
                        $('#comment-list').append(newButton);
                    }
                },
                error: function () {
                    alert('Đã xảy ra lỗi khi tải thêm bình luận.');
                    button.prop('disabled', false).text('Tải lại');
                }
            });
        });
    </script>

    <script>
        document.getElementById('back').addEventListener('click', function (e) {
            e.preventDefault();
            window.history.back();
        });
        const deleteBtn = document.getElementById('delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function (e) {
                e.preventDefault();
                let auth_token = localStorage.getItem('auth_token');
                let id = localStorage.getItem('user_id');

                let confirmDelete = confirm('Bạn có chắc muốn xóa?');
                if (!confirmDelete) return;
                fetch(`/api/post/delete-post/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': `Bearer ${auth_token}`
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success == true) {
                            alert(data.message);
                            window.location.href = '/post/my-posts';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));

            });

        }
    </script>
    <script>
        document.getElementById('like-action').addEventListener('submit', function (e) {
            e.preventDefault();
            let auth_token = localStorage.getItem('auth_token');

            const id = postId;
            fetch(`/api/like-post/${id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': `Bearer ${auth_token}`
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success == true) {
                        let likesCount = document.getElementById('likes-count');
                        likesCount.textContent = parseInt(likesCount.textContent) + 1;
                        let likeAlert = document.getElementById('like-alert');
                        likeAlert.textContent = 'Thích thành công!';
                        likeAlert.style.display = 'block';
                        likeAlert.style.color = 'red';

                        setTimeout(() => {
                            likeAlert.style.display = 'none';
                        }, 3000);
                    } else {
                        alert(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
    <script>
        document.getElementById('createComment').addEventListener('submit', function (e) {
            e.preventDefault();
            const userId = localStorage.getItem('user_id');
            const auth_token = localStorage.getItem('auth_token');

            let content = document.getElementById('comment_content').value;
            fetch('/api/post/create-comment/' + userId + '/' + postId, {
                'method': 'POST',
                'headers': {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': `Bearer ${auth_token}`
                },
                'body': JSON.stringify({
                    content: content
                })
            }).then(response => response.json())
                .then(data => {
                    if (data.status_code == 200) {
                        location.reload();
                    } else {
                        alert(data.message);
                    }
                }).catch(error => console.error('Error:', error));
        })

        document.querySelectorAll('.delete-comment').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();

                const commentId = this.value;
                const userId = localStorage.getItem('user_id');
                const auth_token = localStorage.getItem('auth_token');

                if (!confirm("Bạn có chắc chắn muốn xóa bình luận này?")) return;

                $.ajax({
                    url: `/api/post/comment/delete/${userId}`,
                    method: "POST",
                    data: {
                        id: commentId
                    },
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            alert('Xóa thành công!');
                            location.reload();
                        } else {
                            alert('Không thể xóa bình luận.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                });
            });
        });

        document.getElementById('reportForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const userId = localStorage.getItem('user_id');
            const auth_token = localStorage.getItem('auth_token');
            const id = "{{ $post->id }}";
            const reason = document.getElementById('reason').value;
            const detail = document.getElementById('detail').value;
            if (!confirm("Bạn chắc muốn tố cáo?")) return;
            $.ajax({
                url: `/api/report/post/${userId}`,
                method: "POST",
                data: {
                    id: id,
                    reason: reason,
                    detail: detail,
                },
                headers: {
                    'Authorization': `Bearer ${auth_token}`
                },
                success: function (response) {
                    if (response.status == true) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);

                }
            });
        });
    </script>
@endsection