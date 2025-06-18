@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Quản lý người dùng</h3>
            <form id="filter" class=" row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="searchInput" value="{{ request('searchInput') }}" class="form-control"
                        placeholder="Tìm theo tên, email hoặc id">
                </div>

                <div class="col-md-3">
                    <select name="is_block" class="form-select">
                        <option value=""> Trạng thái </option>
                        <option value="no" {{ request('is_block') }}>
                            Đang hoạt động
                        </option>
                        <option value="yes" {{ request('is_block') }}>
                            Bị khóa tài khoản
                        </option>

                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>

                    <a href="{{ route('admin.user.list') }}" class="btn btn-secondary">Đặt lại</a>
                </div>
            </form>

            <div class="tab-content mt-3" id="userTabContent">

                @forelse($users as $user)
                    <div
                        class="user-card d-flex align-items-center justify-content-between p-3 mb-3 border rounded shadow-sm bg-light">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle me-3" width="60"
                                height="60" alt="Avatar">
                            <div>
                                <h5 class="mb-1">{{ $user->name }} (ID: {{ $user->id }})</h5>
                                <p class="mb-0"><strong>Email:</strong> {{ $user->email }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('admin.user.detail', ['user' => $user->id]) }}"
                                class="btn btn-sm btn-primary me-2">Chi tiết</a>
                            @if ($user->is_block == 'no')
                                <button class="btn btn-sm btn-danger block-btn" data-id="{{ $user->id }}">Khóa</button>
                            @endif
                            @if ($user->is_block == 'yes')
                                <button class="btn btn-sm btn-success unblock-btn" data-id="{{ $user->id }}">Mở</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted">Không có người dùng hoạt động.</p>
                @endforelse

                <div class="pagination mt-3">
                    {{ $users->appends(request()->query())->links() }}

                </div>
            </div>


        </div>
    </div>
    </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.block-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const user = this.dataset.id;
                    if (confirm("Bạn có chắc muốn chặn người dùng này không?")) {
                        $.ajax({
                            url: `/admin/user/block/${user}`,
                            method: "GET",
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi chặn.");
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
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.unblock-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const user = this.dataset.id;
                    if (confirm("Bạn có chắc muốn gỡ chặn người dùng này không?")) {
                        $.ajax({
                            url: `/admin/user/unblock/${user}`,
                            method: "GET",
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi gỡ chặn.");
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