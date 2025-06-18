@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">

    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <a id="back" href="#" class="btn btn-outline-secondary mb-4">
                    &laquo; Thoát
                </a>

                @if($group->users->contains(auth()->user()))

                    <div class="group-banner"
                        style="background: url('{{ asset('storage/' . $group->bgr) }}') center center / cover no-repeat;">
                        <div class="group-title">
                            <h1 class="display-5 fw-bold">{{ $group->name }}</h1>
                        </div>
                    </div>

                    <div class="row gy-4">

                        <div class="col-md-4">
                            <div class="group-sidebar">
                                <h4>Thông tin nhóm</h4>
                                <p><strong>Id:</strong> {{ $group->id }}</p>
                                <p><strong>Mô tả:</strong> {{ $group->description }}</p>
                                <p><strong>Người tạo:</strong> {{ $group->creator->name ?? 'Không rõ' }}</p>
                                <p><strong>Thành viên:</strong> {{ $group->member_count }}</p>
                                <p><strong>Ngày tạo:</strong> {{ $group->created_at->format('d/m/Y H:i') }}</p>


                                <div class="group-action mt-4">
                                    @if($group->users->contains($user))
                                        <a href="{{ route('group.createPost', ['user' => $user, 'group' => $group]) }}"
                                            class="btn btn-success">
                                            <i class="fa-solid fa-pen-to-square me-1"></i>
                                        </a>
                                        <form id="leave" class="d-grid">
                                            @csrf
                                            <button class="btn btn-outline-danger"><i
                                                    class="fa-solid fa-right-from-bracket me-1"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($group->isAdmin(auth()->user()))
                                        <form id="delete" class="d-grid">
                                            @csrf
                                            <button class="btn btn-outline-warning"><i class="fa-solid fa-trash me-1"></i> </button>
                                        </form>
                                        <a href="{{ route('group.manage', ['group' => $group->id]) }}"
                                            class="btn btn-outline-primary">
                                            <i class="fa-solid fa-gear me-1"></i>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>


                        <div class="col-md-8">
                            <div class="group-posts">
                                <h3 class="mb-4 text-primary"> Bài viết gần đây</h3>

                                @if(isset($posts) && count($posts) > 0)
                                    <div class="row g-4">
                                        @foreach($posts as $post)

                                            @include('partials.blog', ['post' => $post])

                                        @endforeach
                                    </div>

                                    <div class="pagination mt-4 d-flex justify-content-center">
                                        {{ $posts->links() }}
                                    </div>
                                @else
                                    <div class="alert alert-info">Chưa có bài viết nào trong nhóm!</div>
                                @endif
                            </div>
                        </div>
                    </div>

                @else
                    <div class="text-center mt-5">
                        <img src="{{ asset('assets/images/no-permission.png') }}" class="img-fluid no-access"
                            alt="No permission">
                        <p class="text-muted mt-3">Bạn không có quyền truy cập nhóm này.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>


    <script>

        document.getElementById('back').addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = `/group/` + userId;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const groupId = {{ $group->id }};

            let auth_token = localStorage.getItem('auth_token');
            document.getElementById('leave').addEventListener('submit', function (e) {
                e.preventDefault();
                if (confirm('Bạn chắc chắn muốn rời nhóm này?')) {
                    $.ajax({
                        url: `/api/group/leave/${userId}/${groupId}`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (res) {
                            alert(res.message);
                            window.location.href = "/group/" + userId;
                        },
                        error: function (err) {
                            alert('Rời nhóm thất bại.');
                            console.log(err.responseJSON);
                        }
                    });
                }
            });

            document.getElementById('delete').addEventListener('submit', function (e) {
                e.preventDefault();
                if (confirm('Bạn chắc chắn muốn xóa nhóm này?')) {
                    $.ajax({
                        url: `/api/group/delete/${groupId}`,
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (res) {
                            alert(res.message);
                            window.location.href = "/group/" + userId;
                        },
                        error: function (err) {
                            alert('Xóa nhóm thất bại.');
                            console.log(err.responseJSON);
                        }
                    });
                }
            });
        });
    </script>

@endsection