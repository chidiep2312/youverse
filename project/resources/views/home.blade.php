@extends('layout.blog')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <div class="main-panel " style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            @if (isset($announcements))
                <div class="container-fluid px-2">
                    @foreach ($announcements as $a)
                        <div class="alert alert-warning d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-2"
                            role="alert" style="border-radius: 15px;">
                            <div class="flex-grow-1">
                                <strong>📢 {{ $a->title }}:</strong> {{ $a->content }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif


            <ul class="nav nav-tabs mb-4" id="postTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="blog-tab" data-bs-toggle="tab" data-bs-target="#blog" type="button"
                        role="tab">Xu hướng</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="another-blog-tab" data-bs-toggle="tab" data-bs-target="#anotherblog"
                        type="button" role="tab">Có thể bạn sẽ quan tâm</button>
                </li>


                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="short-tab" data-bs-toggle="tab" data-bs-target="#short" type="button"
                        role="tab">Dòng trạng thái</button>
                </li>
            </ul>


            <div class="tab-content" id="postTabsContent">
                <div class="mb-4">

                    @foreach ($tags as $tag)
                        <a href="{{ route('tag', $tag->id) }}"
                            style="padding-top:5px;margin-top:5px;font-size:14px;background-color:rgb(253, 254, 255); color:#94a7fc; text-decoration:none;border-radius:25px;"
                            class="px-3 rounded-full text-sm font-medium">
                            #{{ $tag->tag_name }}
                        </a>
                    @endforeach
                </div>
                <div class="tab-pane fade show active" id="blog" role="tabpanel">
                    @if ($mostLikes->isNotEmpty())
                        <h3 class="font-weight-bold mb-4">Bài viết được yêu thích nhất</h3>
                        <div class="row">
                            @foreach ($mostLikes as $like)
                                @include('partials.blog', ['post' => $like])
                            @endforeach

                        </div>
                        <div class="pagination mt-3">
                            {{ $mostLikes->appends(request()->except('mostLikes'))->links() }}
                        </div>
                    @endif


                    @if ($mostViews->isNotEmpty())
                        <h3 class="font-weight-bold">Bài viết có lượt view cao nhất</h3>
                        <div class="row">
                            @foreach ($mostViews as $view)
                                @include('partials.blog', ['post' => $view])
                            @endforeach
                        </div>
                        <div class="pagination mt-3">
                            {{ $mostViews->appends(request()->except('mostViews'))->links() }}
                        </div>
                    @endif

                    @if ($newest_posts->isNotEmpty())
                        <h3 class="font-weight-bold">Bài viết gần đây</h3>
                        <div class="row">
                            @foreach ($newest_posts as $newest)
                                @include('partials.blog-bigger', ['post' => $newest])
                            @endforeach


                        </div>
                        <div class="pagination mt-3">
                            {{ $newest_posts->appends(request()->except('newest_posts'))->links() }}
                        </div>
                    @endif

                </div>


                <div class="tab-pane fade" id="anotherblog" role="tabpanel">
                    @if ($suggested->isNotEmpty())
                        <h3 class="font-weight-bold">Đề xuất cho bạn</h3>
                        <div class="row">
                            @foreach ($suggested as $newest)
                                @include('partials.blog-bigger', ['post' => $newest])
                            @endforeach

                        </div>
                        <div class="pagination mt-3">
                            {{ $suggested->appends(['activeTab' => 'anotherblog'])->links() }}
                        </div>
                    @endif


                </div>

                <div class="tab-pane fade" id="short" role="tabpanel">

                    @if (isset($threads))
                        @foreach ($threads as $thread)
                            @include('partials.thread', ['thread' => $thread])
                        @endforeach

                    @endif
                    <div class="pagination mt-4">
                        {{ $threads->appends(request()->except('threads'))->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.querySelectorAll('.toggle-comments-btn').forEach(button => {
            button.addEventListener('click', function () {
                const threadId = this.getAttribute('data-thread-id');
                const section = document.getElementById('comments-' + threadId);
                if (section.style.display === 'none') {
                    section.style.display = 'block';
                    section.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                } else {
                    section.style.display = 'none';
                }
            });

        });

        document.querySelectorAll('.comment-form').forEach(form => {
            const threadId = form.querySelector('.thread_id').value;

            window.Echo.channel(`Thread${threadId}`)
                .listen('.comment.post', (e) => {
                    console.log(e);
                    const commentList = document.getElementById('comment-list-' + threadId);

                    if (commentList) {
                        const html = `
                                                                                                                                                                                                                                                                                                                     <div class="comment-item d-flex justify-content-between align-items-center px-3 py-2 rounded">
                                                                                                                                                                            <div class="text-truncate">
                                                                                                                                                                                <strong class="me-1">${e.commenter.name}</strong>
                                                                                                                                                                                <span class="text-muted small">${e.content}</span>
                                                                                                                                                                            </div>
                                                                                                                                                                            ${e.commenter.id === parseInt(localStorage.getItem('user_id')) ? `
                                                                                                                                                                                <button class="btn delete-comment" value="${e.id}" title="Xóa bình luận">
                                                                                                                                                                                    <i class="fa-solid fa-xmark"></i>
                                                                                                                                                                                </button>` : ''}
                                                                                                                                                                        </div>`;
                        commentList.insertAdjacentHTML('beforeend', html);
                    }
                });

            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const userId = localStorage.getItem('user_id');
                const auth_token = localStorage.getItem('auth_token');
                const content = form.querySelector('.content').value;

                $.ajax({
                    url: `/api/thread/comment/${userId}/0/${threadId}`,
                    method: "POST",
                    data: {
                        content: content,
                    },
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.status_code === 200) {
                            form.querySelector('.content').value = '';

                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);
                    }
                });
            });
        });

        document.querySelectorAll('.delete-comment').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const commentId = this.value;
                const userId = localStorage.getItem('user_id');
                const auth_token = localStorage.getItem('auth_token');

                if (!confirm("Bạn có chắc chắn muốn xóa bình luận này?")) return;

                $.ajax({
                    url: `/api/thread/delete-comment/${userId}`,
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
    </script>
    <script>

    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    history.replaceState(null, null, '#' + btn.getAttribute('data-bs-target')
                        .substring(1));
                });
            });
            var hash = window.location.hash;
            if (hash) {
                var tabTrigger = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (tabTrigger) {
                    new bootstrap.Tab(tabTrigger).show();
                }
            }
        });
    </script>

@endsection