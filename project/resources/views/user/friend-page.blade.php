@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/user-page.css') }}">

    <div class="main-panel w-100">
        @if ($user->isBlockedBy($user->id))
            <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
                <h1 class="text-danger ">Bạn không thể xem trang này</h1>
            </div>

        @else
            <div class="content-wrapper"
                style="border-radius:25px;background: url({{ asset('storage/' . $user->bgr) }}) center/cover no-repeat;">

                <div class="container">
                    <div class="row gy-4">

                        <div class="col-md-4">
                            <div class="card shadow-sm p-4 text-center bg-white rounded-4">
                                <img src="{{ asset('storage/' . $user->avatar) ?? asset('assets/images/default.png') }}"
                                    alt="User Avatar" class="rounded-circle shadow-sm mx-auto mb-3"
                                    style="width: 150px; height: 150px; object-fit: cover; border: 4px solid #fff;">

                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-1"><i class="fa-solid fa-envelope me-2"></i>{{ $user->email }}</p>
                                <p class="text-muted mb-2"><i class="fa-solid fa-calendar me-2"></i>{{ $user->created_at }}
                                </p>
                                <p class="fst-italic small">
                                    <a href="#" data-toggle="modal" data-target="#changeSloganModal"
                                        class="text-decoration-none text-muted">
                                        {!! $user->slogan ?? 'Xin chào' !!}
                                    </a>
                                </p>
                                @if (auth()->user()->isFriend($user->id))
                                    <a href="#" id="unfriend"
                                        class="btn btn-danger rounded-pill d-flex align-items-center justify-content-center gap-2"
                                        style="height: 40px; min-width: 140px;">
                                        <i class="bi bi-person-dash"></i>
                                        <span>Bỏ kết bạn</span>
                                    </a>
                                @endif

                                @if(auth()->user()->blockUser($user->id))
                                    <a href="#" id="unblock"
                                        class="btn btn-success rounded-pill d-flex align-items-center justify-content-center gap-2"
                                        style="height: 40px; min-width: 140px;">
                                        <i class="bi bi-person-dash"></i>
                                        <span>Bỏ chặn</span>
                                    </a>
                                @else
                                    <a href="#" id="block"
                                        class="btn btn-warning rounded-pill d-flex align-items-center justify-content-center gap-2"
                                        style="height: 40px; min-width: 140px;">
                                        <i class="bi bi-person-dash"></i>
                                        <span>Chặn</span>
                                    </a>
                                @endif
                            </div>

                            <div class="folder card mt-4 p-3 shadow-sm bg-white rounded-4">
                                <h5 class="mb-3"><i class="bi bi-folder-fill me-2"></i> Thư mục</h5>
                                <ul class="list-unstyled">
                                    @if ($folders)
                                        @foreach ($folders as $folder)
                                            <li class="folder-item d-flex justify-content-between align-items-center p-2 rounded mb-2">
                                                <a href="{{ route('folder.user-view', ['folder' => $folder->id, 'userId' => $user->id]) }}"
                                                    class="text-decoration-none text-dark d-flex align-items-center">
                                                    <i class="bi bi-folder2-open me-2 text-warning" style="font-size: 1.2rem;"></i>
                                                    {{ $folder->name }}
                                                </a>
                                                <span class="badge bg-secondary">{{ $folder->posts_count }}</span>
                                            </li>
                                        @endforeach
                                    @endif
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
                                                        class="text-dark">
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
                                    <h4 class="mb-0"><i class="fa-regular fa-address-card"></i> Chia sẻ trạng thái
                                    </h4>

                                </div>
                                <div style="max-height: 500px; overflow-y: auto;" class="custom-scroll">
                                    @if ($threads->count() > 0)
                                        @foreach ($threads as $thread)
                                            @include('partials.thread', ['thread' => $thread])
                                        @endforeach
                                    @else
                                        Người dùng chưa chia sẻ trạng thái
                                    @endif
                                </div>
                            </div>

                            @if (isset($newest_posts))
                                <div class="card p-4 shadow-sm bg-white rounded-4 mb-4">
                                    <h4 class="mb-3">Bài viết gần đây</h4>
                                    <div class="row">
                                        @if ($newest_posts->count() > 0)
                                            @foreach ($newest_posts as $post)
                                                @include('partials.blog-bigger', ['post' => $post])
                                            @endforeach
                                        @else
                                            Người dùng chưa tạo bài viết nào
                                        @endif
                                    </div>
                                    <div class="pagination mt-3">
                                        {{ $newest_posts->appends(request()->except('newest_posts'))->links() }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
        @endif
        </div>
    </div>
    <script>
        const unfr = document.getElementById('unfriend');
        const block = document.getElementById('block');
        const unblock = document.getElementById('unblock');
        const friend_id = {{ $user->id }}
                                                                                                                                       if (unfr) {
            unfr.addEventListener('click', function (e) {
                $.ajax({
                    url: `/api/personal/unfriend/${friend_id}`,
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            alert(response.message);
                            unfr.classList.remove('btn-danger');
                            unfr.classList.add('btn-secondary');
                            unfr.classList.add('disabled');
                            unfr.innerHTML = '<i class="bi bi-check2-circle"></i> <span>Đã hủy kết bạn</span>';
                            unfr.style.pointerEvents = "none";
                        } else {
                            alert('Có lỗi xảy ra.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                })
            })
        }

        if (block) {
            block.addEventListener('click', function (e) {
                $.ajax({
                    url: `/api/personal/block/${friend_id}`,
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.status == 'success') {

                            block.classList.remove('btn-warning');
                            block.classList.add('btn-secondary');
                            block.classList.add('disabled');
                            block.innerHTML = '<i class="bi bi-check2-circle"></i> <span>Đã chặn</span>';
                            block.style.pointerEvents = "none";

                        } else {
                            alert('Có lỗi xảy ra.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                })
            })
        }
        if (unblock) {
            unblock.addEventListener('click', function (e) {
                $.ajax({
                    url: `/api/personal/unblock/${friend_id}`,
                    method: "POST",
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.status == 'success') {
                            unblock.classList.remove('btn-warning');
                            unblock.classList.add('btn-secondary');
                            unblock.classList.add('disabled');
                            unblock.innerHTML = '<i class="bi bi-check2-circle"></i> <span>Đã gỡ chặn</span>';
                            unblock.style.pointerEvents = "none";
                        } else {
                            alert('Có lỗi xảy ra.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                })
            })
        }
    </script>
@endsection