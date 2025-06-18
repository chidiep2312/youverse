@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Danh sách thẻ</h3>
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <form id="filter" class="d-flex align-items-center gap-2">

                    <div class="col">
                        <select name="sort" class="form-select">
                            <option value="">-- Sắp xếp theo --</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên A-Z</option>
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Cũ nhất</option>
                        </select>
                    </div>

                    <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <button id="delete-selected" class="btn btn-outline-danger shadow-sm" title="Xóa đã chọn">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                    <a href="{{ route('admin.tag.index') }}" class="btn btn-secondary shadow-sm">Đặt lại</a>
                </form>

                <button class="btn btn-primary d-flex align-items-center shadow-sm" style="border-radius: 25px;"
                    data-bs-toggle="modal" data-bs-target="#createTagModal">
                    <i class="fa-solid fa-plus me-2"></i> Tạo mới
                </button>
            </div>

            @include('modals.tag')
            <div style="max-width:1000px;" class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>STT</th>
                            <th>Id</th>
                            <th>Thẻ</th>
                            <th>Số lượng bài</th>

                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (isset($tags))
                            @foreach ($tags as $index => $tag)
                                <tr>
                                    <td><input type="checkbox" class="select-tag" value="{{ $tag->id }}"></td>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $tag->id }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($tag->tag_name, 20)) !!}</td>
                                    <td>{{ $tag->posts->count() }}</td>

                                    <td class="text-center">

                                        <button data-id="{{ $tag->id }}" class="btn btn-sm btn-danger delete-tag">Xoá</button>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
            @if (isset($tags))
                <div class="pagination mt-3">
                    {{ $tags->links() }}

                </div>
            @endif
        </div>

    </div>
    </div>
    </div>
    </div>
    <script>
        const auth_token = localStorage.getItem('auth_token');
        document.addEventListener('DOMContentLoaded', function () {

            const selectAllCheckbox = document.getElementById('select-all');
            selectAllCheckbox.addEventListener('change', function () {
                const checkboxes = document.querySelectorAll('.select-tag');
                checkboxes.forEach(chk => chk.checked = selectAllCheckbox.checked);
            });

            document.getElementById('delete-selected').addEventListener('click', function () {
                const selected = Array.from(document.querySelectorAll('.select-tag:checked')).map(cb => cb
                    .value);
                if (selected.length === 0) {
                    alert('Vui lòng chọn ít nhất một bài viết để xóa.');
                    return;
                }

                if (confirm(`Bạn có chắc muốn xóa ${selected.length} bài viết đã chọn không?`)) {
                    $.ajax({
                        url: `/api/admin/tag/delete-all`,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        data: {
                            ids: selected
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
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-tag').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const tag = this.dataset.id;

                    if (confirm("Bạn có chắc muốn xóa thẻ này không này không?")) {
                        $.ajax({
                            url: `/api/admin/tag/delete/${tag}`,
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
                                    alert(response.message);
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
    <script>
        document.getElementById('create-tag').addEventListener('click', function (e) {
            e.preventDefault();

            let tag_name = document.getElementById('tag_name').value;
            let auth_token = localStorage.getItem('auth_token');

            $.ajax({
                url: `/api/admin/tag/save`,
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Authorization': `Bearer ${auth_token}`
                },
                data: {
                    tag_name: tag_name
                },
                success: function (response) {
                    if (response.success == true) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert("Có lỗi xảy ra khi tạo tag.");
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Lỗi:", error);
                }
            });

        });
    </script>

@endsection