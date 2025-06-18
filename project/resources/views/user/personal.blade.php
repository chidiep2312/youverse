<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>YOUVERSE</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/perfect-scrollbar/1.5.3/css/perfect-scrollbar.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/js/select.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vertical-layout-light/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/@mdi/font/css/materialdesignicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/forum.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/themify-icons@0.1.2/css/themify-icons.css">

    <link rel="shortcut icon" href="{{ asset('assets/images/blog-logo.png') }}" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container-scroller">
        @include('components.navbar')
        <div class="container-fluid page-body-wrapper">
            @include('components.setting-panel')
            @section('content')
            <link rel="stylesheet" href="{{ asset('assets/css/user-page.css') }}">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
            <div class="main-panel" style="width:100%;">
                <div class="content-wrapper"
                    style="background: url({{ asset('storage/' . $user->bgr) }}) center/cover no-repeat;">
                    <a id="back" href="#"
                        class="btn btn-outline-primary d-inline-flex align-items-center px-3 py-2 rounded-pill shadow-sm"
                        style="background-color: #fff;">
                        <i class="ti-arrow-left me-2"></i> Quay lại
                    </a>

                    <div class="container py-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="account-info card p-3 shadow-sm mb-4 bg-white">
                                    <div class="action-buttons d-flex flex-column position-absolute"
                                        style="top: 20px; right: 20px; z-index: 10;">
                                        <a href="#" class="btn btn-sm rounded-circle mb-2 shadow-sm"
                                            style="width: 40px; height: 40px; background: rgba(0,0,0,0.05);"
                                            data-toggle="modal" data-target="#uploadAvatarModal"
                                            title="Cập nhật ảnh đại diện">
                                            <i class="bi bi-upload text-dark"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm rounded-circle mb-2 shadow-sm"
                                            style="width: 40px; height: 40px; background: rgba(0,0,0,0.05);"
                                            data-toggle="modal" data-target="#uploadBgrModal" title="Cập nhật ảnh bìa">
                                            <i class="bi bi-image text-dark"></i>
                                        </a>
                                        <a href="#" class="btn btn-sm rounded-circle shadow-sm"
                                            style="width: 40px; height: 40px; background: rgba(0,0,0,0.05);"
                                            data-toggle="modal" data-target="#editNameModal" title="Sửa tên">
                                            <i class="bi bi-pencil text-dark"></i>
                                        </a>
                                    </div>
                                    <div class="text-center mb-3">
                                        <img src="{{ asset('storage/' . $user->avatar) ?? asset('assets/images/default.png')}}"
                                            alt="User Avatar" class="rounded-circle mb-3 shadow"
                                            style="width: 150px; height: 150px; object-fit: cover; border: 5px solid white;">
                                    </div>
                                    <p><i class="fa-solid fa-user" style="margin-right:20px;"></i>{{ $user->name }}
                                    </p>
                                    <p><i class="fa-solid fa-envelope" style="margin-right:20px;"></i>{{ $user->email }}
                                    </p>
                                    <p><i class="fa-solid fa-calendar" style="margin-right:20px;"></i>
                                        {{ $user->created_at }}</p>
                                    <p class="text-center fst-italic"><a href="#" data-toggle="modal"
                                            data-target="#changeSloganModal"
                                            style="  color: inherit;">{!! $user->slogan ?? 'Xin chào' !!}</a></p>
                                </div>

                                <div class=" card p-3 shadow-sm bg-white folder">
                                    <h5 class="mb-3"><i class="bi bi-folder-fill me-2"></i> Thư mục của tôi</h5>
                                    <ul class="list-unstyled">
                                        @foreach ($folders as $folder)
                                            <li
                                                class="folder-item d-flex justify-content-between align-items-center p-2 rounded mb-2">
                                                <a href="{{ url('folder/detail/' . $user->id . '/' . $folder->id) }}"
                                                    class="text-decoration-none text-dark d-flex align-items-center">
                                                    <i class="bi bi-folder2-open me-2 text-warning"
                                                        style="font-size: 1.2rem;"></i>
                                                    {{ $folder->name }}
                                                </a>
                                                <span class="badge bg-secondary">{{ $folder->posts_count }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="col-md-8">
                                @if($topics->count() > 0)
                                    <div class="card shadow-sm p-3 mb-4">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <h4 class="mb-0">Chủ đề
                                            </h4>
                                        </div>
                                        <div style="max-height: 500px; overflow-y: auto;" class="custom-scroll">
                                            <ul class="list-group shadow-sm rounded-3">
                                                @foreach ($topics as $topic)
                                                    <li style="border:none"
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <a href="{{ route('thread.detail-topic', ['topic' => $topic->id]) }}"
                                                            class="text-decoration-none text-dark fw-semibold">
                                                            <strong style="color:rgb(81, 141, 190)"> {{ $topic->title }}
                                                            </strong> -
                                                            {{ $topic->comments->count() }} lượt thảo luận
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                    </div>
                                @endif
                                <div class="card shadow-sm p-3 mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h4 class="mb-0">Chia sẻ trạng thái
                                        </h4>
                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal"
                                            data-target="#createThreadModal">
                                            <i class="bi bi-plus-circle"></i> Mới
                                        </button>
                                    </div>
                                    <div style="max-height: 500px; overflow-y: auto;" class="custom-scroll">
                                        @foreach ($threads as $thread)
                                            @include('partials.thread', ['thread' => $thread])
                                        @endforeach
                                    </div>
                                </div>

                                @if (isset($newest_posts))
                                    <div class="mb-4">
                                        <h4>Bài viết gần đây</h4>
                                        <div class="row">
                                            @foreach ($newest_posts as $post)
                                                @include('partials.blog-bigger', ['post' => $post])
                                            @endforeach
                                            <div class="pagination mt-3">
                                                {{ $newest_posts->appends(request()->except('newest_posts'))->links() }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('modals.create-thread')
            @include('modals.create-slogan')
            @include('modals.edit-name')
            @include('modals.upload-avatar')
            @include('modals.upload-backgr')

            <script>
                document.getElementById('back').addEventListener('click', function (e) {
                    e.preventDefault();
                    window.history.back();
                });
            </script>
            <script>
                document.getElementById('update-avatar').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const id = localStorage.getItem('user_id');
                    const auth_token = localStorage.getItem('auth_token');
                    const form = document.querySelector('#update-avatar');
                    const formData = new FormData(form);
                    fetch(`/api/personal/update-avatar/${id}`, {
                        "method": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        body: formData
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success == true) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.error);
                            }
                        }).catch(error => console.error("Error:", error));
                })

                document.getElementById('changeSlogan').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const id = localStorage.getItem('user_id');
                    const auth_token = localStorage.getItem('auth_token');
                    let slogan = document.getElementById('slogan').value;
                    $.ajax({
                        url: `/api/personal/slogan/${id}`,
                        method: "POST",
                        data: {
                            slogan: slogan,
                        },
                        headers: {

                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (response) {

                            if (response.status_code === 200) {
                                alert(response.message);
                                location.reload();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error: ", error);
                        }
                    });

                })
            </script>

            <script>
                document.getElementById('update-bgr').addEventListener('submit', function (e) {
                    e.preventDefault();
                    const id = localStorage.getItem('user_id');
                    const auth_token = localStorage.getItem('auth_token');
                    const form = document.querySelector('#update-bgr');
                    const formData = new FormData(form);
                    fetch(`/api/personal/update-bgr/${id}`, {
                        "method": "POST",
                        "headers": {
                            "X-CSRF-TOKEN": '{{ csrf_token() }}',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        body: formData
                    }).then(response => response.json())
                        .then(data => {
                            if (data.success == true) {
                                alert(data.message);
                                location.reload();
                            } else {
                                alert(data.error);
                            }
                        }).catch(error => console.error("Error:", error));
                })
            </script>

            <script>
                document.getElementById('updateName').addEventListener('submit', function (e) {
                    e.preventDefault();
                    let name = document.getElementById('new-name').value;
                    let userId = localStorage.getItem('user_id');
                    let auth_token = localStorage.getItem('auth_token');

                    $.ajax({
                        url: `/api/personal/update-name/${userId}`,
                        method: "POST",
                        data: {
                            name: name,
                        },
                        headers: {

                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (response) {

                            if (response.status_code === 200) {
                                alert(response.message);
                                location.reload();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error: ", error);
                        }
                    });

                });

                document.getElementById('createThread').addEventListener('submit', function (e) {
                    e.preventDefault();
                    let content = document.getElementById('content').value;
                    let userId = localStorage.getItem('user_id');
                    let auth_token = localStorage.getItem('auth_token');
                    console.log(userId);
                    $.ajax({
                        url: `/api/thread/save/${userId}`,
                        method: "POST",
                        data: {
                            content: content,
                        },
                        headers: {

                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (response) {

                            if (response.status_code === 200) {
                                alert(response.message);
                                location.reload();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error: ", error);
                        }
                    });

                });
            </script>
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
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        const threadId = this.querySelector('.thread_id').value;
                        const userId = localStorage.getItem('user_id');
                        const auth_token = localStorage.getItem('auth_token');
                        const content = this.querySelector('.content').value;
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
                                    alert(response.message);
                                    location.reload();
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
                        console.log("userId = ", userId);
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
        </div>
        @include('components.footer')
    </div>


</body>

</html>