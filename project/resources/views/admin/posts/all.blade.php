@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Danh sách bài viết</h3>
            <form id="filter" class=" row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="title" value="{{ request('title') }}" class="form-control"
                        placeholder="Tìm theo tiêu đề">
                </div>
                <div class="col-md-3">
                    <input type="text" name="user" value="{{ request('user') }}" class="form-control"
                        placeholder="Tìm theo người viết">
                </div>
                <div class="col-md-3">
                    <select name="tag_id" class="form-select">
                        <option value="">Lọc theo thẻ </option>
                        @foreach($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <button id="delete-selected" class="btn"><i class="fa-solid fa-trash"></i></i></button>
                    <a href="{{ route('admin.post.list-post') }}" class="btn btn-secondary">Đặt lại</a>
                </div>
            </form>

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
                                    <td><a style="color:rgb(36, 33, 33)"
                                            href="{{ route('admin.user.detail', ['user' => $post->user->id]) }}">{{ $post->user->name }}</a>
                                    </td>
                                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($post->des, 100)) !!}</td>
                                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.post.detail-post', ['post' => $post->id]) }}"
                                            class="btn btn-sm btn-primary">Xem</a>
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

            const selectAllCheckbox = document.getElementById('select-all');
            selectAllCheckbox.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.select-post');
                checkboxes.forEach(chk => chk.checked = selectAllCheckbox.checked);
            });


            document.getElementById('delete-selected').addEventListener('click', function () {
                const selected = Array.from(document.querySelectorAll('.select-post:checked')).map(cb => cb.value);
                if (selected.length === 0) {
                    alert('Vui lòng chọn ít nhất một bài viết để xóa.');
                    return;
                }
                const auth_token = localStorage.getItem('auth_token');
                if (confirm(`Bạn có chắc muốn xóa ${selected.length} bài viết đã chọn không?`)) {
                    $.ajax({
                        url: `/api/admin/post/delete-multi`,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        data: { ids: selected },
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
    </script>

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

        });
    </script>
@endsection