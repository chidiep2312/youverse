@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="short-post-list">
                <div class="short-post-card">
                    <div class="short-post-header">
                        <div class="user-info">
                            <img src="{{ asset('storage/' . $thread->user->avatar) }}" class="rounded-circle shadow-sm"
                                width="48" height="48" style="object-fit: cover; border: 2px solid #fff;">
                            <strong>{{ $thread->user->name }}</strong>
                            <small>{{  $thread->created_at->diffForHumans()  }}</small>
                        </div>
                    </div>
                    <p style="margin-top:10px;" class="short-post-excerpt">
                        {!! $thread->content!!}
                    </p>
                    <div class="short-post-footer pt-4 mt-4 border-top">
                        <div id="comment-list-{{ $thread->id }}">
                            @foreach($thread->comments as $c)
                                <div class="comment-box d-flex mb-4">
                                    <img src="{{ asset('storage/' . $c->user->avatar) }}" class="rounded-circle me-3"
                                        alt="avatar" style="width: 50px; height: 50px; object-fit: cover;">

                                    <div class="flex-grow-1 bg-light p-3 rounded-3 shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong class="text-dark">{{ $c->user->name }}</strong>
                                            <small class="text-muted d-flex align-items-center">
                                                {{ $c->created_at->diffForHumans() }}
                                                @if(auth()->id() == $c->user->id)
                                                    <button class="delete-comment border-0 bg-transparent ms-2" value="{{ $c->id }}"
                                                        title="Xoá bình luận">
                                                        <i class="fa-solid fa-trash-can text-secondary"
                                                            style="font-size: 1rem;"></i>
                                                    </button>
                                                @endif
                                            </small>
                                        </div>
                                        <div>
                                            {{ $c->content }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @include('partials.comment-form', ['thread' => $thread])
                    </div>

                </div>
            </div>
        </div>

    </div>
    </div>
    <script>
        document.querySelectorAll('.comment-form').forEach(form => {
            const threadId = form.querySelector('.thread_id').value;

            window.Echo.channel(`Thread${threadId}`)
                .listen('.comment.post', (e) => {
                    console.log(e);
                    const commentList = document.getElementById('comment-list-' + threadId);

                    if (commentList) {
                        const html = `  <div class="comment-box d-flex mb-4">
                                                                                   <img src="/storage/${e.commenter.avatar}" class="rounded-circle me-3" alt="avatar"
                                                        style="width: 50px; height: 50px; object-fit: cover;">


                                                                                    <div class="flex-grow-1 bg-light p-3 rounded-3 shadow-sm">
                                                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                                                            <strong class="text-dark">${e.commenter.name}</strong>
                                                                                            <small class="text-muted d-flex align-items-center">
                                                                                              ${e.created_at}
                                                                                                   ${e.commenter.id === parseInt(localStorage.getItem('user_id')) ? `
                                                                                                         <button class="delete-comment border-0 bg-transparent ms-2" value="${e.id}"
                                                                                                        title="Xoá bình luận">
                                                                                                        <i class="fa-solid fa-trash-can text-secondary" style="font-size: 1rem;"></i>
                                                                                                    </button>` : ''}

                                                                                            </small>
                                                                                        </div>
                                                                                        <div>
                                                                                           ${e.content}
                                                                                        </div>
                                                                                    </div>
                                                                                </div>`;
                        commentList.insertAdjacentHTML('beforeend', html);
                    }
                    commentList.scrollTop = commentList.scrollHeight;
                    deleteComment();
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
        function deleteComment() {
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
        }
        deleteComment();
    </script>

@endsection