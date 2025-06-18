@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Danh sách bài viết vi phạm</h3>
            <button id="delete-selected" class="btn btn-danger mb-3">Xoá các bài đã chọn</button>

            <div style="max-width:1000px;" class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Thẻ</th>
                            <th>Ảnh đại diện</th>
                            <th>Người viết</th>
                            <th>Mô tả</th>
                            <th>Ngày viết</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($posts))
                            @foreach ($posts as $index => $post)
                                <tr>
                                    <td><input type="checkbox" class="select-post" value="{{ $post->id }}"></td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($post->title, 20)) !!}</td>
                                    <td>{{ $post->tag->tag_name }}</td>
                                    <td> <img src="{{ asset('storage/' . $post->user->avatar) }}"
                                            class="rounded-circle me-3 shadow-sm"
                                            style="width: 48px; height: 48px; object-fit: cover;"></td>
                                    <td>{{ $post->user->name }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($post->des, 100)) !!}</td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.post.detail-flagged-post', ['id' => $post->id]) }}"
                                            class="btn btn-sm btn-primary">Xem</a>
                                        <button data-id="{{ $post->id }}" class="btn btn-sm btn-success approve-post">Duyệt</button>
                                        <button data-id="{{ $post->id }}" class="btn btn-sm btn-danger delete-post">Xoá</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @if (isset($posts))
                <div class="pagination mt-3">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

    </div>
    </div>
    </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-post').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const post = this.dataset.id;
                    const auth_token = localStorage.getItem('auth_token');
                    if (confirm("Bạn có chắc muốn xóa bài viết này không này không?")) {
                        $.ajax({
                            url: `/api/admin/post/delete/${post}`,
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${auth_token}`
                            },
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
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
            });
            document.querySelectorAll('.approve-post').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const auth_token = localStorage.getItem('auth_token');
                    if (confirm("Bạn có chắc muốn duyệt bài viết này không này không?")) {
                        $.ajax({
                            url: `/api/admin/post/approve/${id}`,
                            method: "POST",
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Authorization': `Bearer ${auth_token}`
                            },
                            success: function (response) {
                                if (response.success == true) {
                                    alert('Hoàn tất!');
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

            });

        });
    </script>
@endsection