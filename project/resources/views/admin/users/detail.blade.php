@extends('admin.layout')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user-detail.css') }}">

    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            @if ($user)
                <div class="user-detail-card">
                    <div class="user-detail-header">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                        <div class="user-info">
                            <p><strong>ID:</strong> <span>{{ $user->id }}</span></p>
                            <p><strong>Tên:</strong> <span>{{ $user->name }}</span></p>
                            <p><strong>Email:</strong> <span>{{ $user->email }}</span></p>
                            <p><strong>Số bài viết:</strong> <span>{{ $posts }}</span></p>
                            <p><strong>Ngày tham gia:</strong> <span>{{ $user->created_at->format('d/m/Y') }}</span></p>
                        </div>
                    </div>

                    <div class="user-actions">
                        <a href="#" data-id="{{ $user->id }}" id="sendWarningEmail"><i
                                class="fa-solid fa-triangle-exclamation"></i> Gửi cảnh báo</a>

                        <a href="#" data-id="{{ $user->id }}" id="block-user" class="delete"><i class="fa-solid fa-trash"></i>
                            Khóa tài khoản</a>
                    </div>
                </div>
            @else
                <p class="text-center text-danger">Không tìm thấy thông tin người dùng.</p>
            @endif

            <div class="back-link">
                <a href="#" id="back">← Quay lại danh sách</a>
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
        document.getElementById('sendWarningEmail').addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const auth_token = localStorage.getItem('auth_token');
            $.ajax({
                url: `/admin/user/warning/${id}`,
                method: "GET",
                success: function (response) {
                    if (response.success == true) {
                        alert(response.message);
                    } else {
                        alert("Có lỗi xảy ra.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Lỗi:", error);
                }

            })
        })


        document.getElementById('block-user').addEventListener('click', function (e) {
            e.preventDefault();
            const id = this.dataset.id;
            const auth_token = localStorage.getItem('auth_token');
            $.ajax({
                url: `/admin/user/block/${id}`,
                method: "GET",
                success: function (response) {
                    if (response.success == true) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert("Có lỗi xảy ra.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Lỗi:", error);
                }

            })
        })
    </script>
@endsection