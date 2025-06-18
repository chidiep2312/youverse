@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4"> Quản lý nhóm</h3>
            <form id="filter" class=" row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                        placeholder="Tìm theo tên chủ nhóm">
                </div>
                <div class="col-md-3">
                    <input type="text" name="member_count" value="{{ request('member_count') }}" class="form-control"
                        placeholder="Tìm theo số lượng thành viên">
                </div>
                <div class="col-md-3">
                    <select name="is_active" class="form-select">
                        <option value="">-- Trạng thái --</option>

                        <option value="1" {{ request('is_active') }}>
                            Hoạt động
                        </option>

                        <option value="0" {{ request('is_active') }}>
                            Khóa
                        </option>

                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    <button id="inactive-selected" class="btn">Khóa nhóm</i></i></button>
                    <a href="{{ route('admin.group.list') }}" class="btn btn-secondary">Đặt lại</a>
                </div>
            </form>
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th scope="col">STT</th>
                            <th scope="col">Tên nhóm</th>
                            <th scope="col">Người tạo</th>
                            <th scope="col">Thành viên</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col" class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($groups->count() > 0)
                            @foreach ($groups as $index => $group)
                                <tr>
                                    <td><input type="checkbox" class="select-post" value="{{ $group->id }}"></td>
                                    <td>{{ $index + 1 }}</td>
                                    <td><strong>{{ $group->name }}</strong></td>
                                    <td>{{ $group->creator->name ?? 'Chưa xác định'}}</td>
                                    <td>{{ $group->member_count }} thành viên</td>
                                    <td>{{ $group->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @if ($group->is_active == true)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Bị khóa</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('admin.group.detail', ['group' => $group->id]) }}"
                                            class="btn btn-sm btn-info me-1">Xem</a>

                                        @if ($group->is_active == true)
                                            <button value="{{ $group->id }}" class="btn btn-sm btn-warning inactive">Khóa</button>
                                        @else
                                            <button value="{{ $group->id }}" class="btn btn-sm btn-success active">Mở</button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
                <div class="pagination mt-3">
                    {{ $groups->appends(request()->except('groups'))->links() }}

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


            document.getElementById('inactive-selected').addEventListener('click', function () {
                const selected = Array.from(document.querySelectorAll('.select-post:checked')).map(cb => cb.value);
                if (selected.length === 0) {
                    alert('Vui lòng chọn ít nhất một nhóm.');
                    return;
                }
                const auth_token = localStorage.getItem('auth_token');
                if (confirm(`Bạn có chắc muốn thực hiện khóa ${selected.length} nhóm đã chọn không?`)) {
                    $.ajax({
                        url: '/api/admin/group/inactive-multi/',
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        data: { ids: selected },
                        success: function (response) {
                            if (response.success == true) {
                                alert(response.message);

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
            document.querySelectorAll('.inactive').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const group = this.value;
                    if (confirm("Bạn có chắc muốn vô hiệu nhóm này không?")) {
                        $.ajax({
                            url: `/admin/group/inactive/${group}`,
                            method: "GET",
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi vô hiệu nhóm này!");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Lỗi:", error);
                            }
                        });
                    }
                });

            });

            document.querySelectorAll('.active').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const group = this.value;
                    if (confirm("Bạn có chắc muốn kích hoạt nhóm này không?")) {
                        $.ajax({
                            url: `/admin/group/active/${group}`,
                            method: "GET",
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi kích hoạt nhóm này!");
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