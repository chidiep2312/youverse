@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">

            <h3 class="mb-4"><i class="bi bi-flag-fill text-danger"></i> Danh sách trạng thái - chủ đề</h3>

            <form id="filter" class=" row g-3 mb-4">
                <div class="col-md-3">
                    <input type="text" name="searchInput" value="{{ request('searchInput') }}" class="form-control"
                        placeholder="Tìm theo tên, email hoặc id người viết">
                </div>
                <div class="col-md-3">
                    <input type="date" name="created_at" value="{{ request('created_at') }}" class="form-control"
                        placeholder="Tìm theo ngày">
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" name="is_pinned">
                        <label class="form-check-label" for="flexCheckDefault">
                            Đang ghim
                        </label>
                    </div>
                </div>

                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn"><i class="fa-solid fa-magnifying-glass"></i></button>

                    <a href="{{ route('admin.thread.list') }}" class="btn btn-secondary">Đặt lại</a>
                </div>
            </form>

            <ul class="nav nav-tabs" id="reportTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                        type="button" role="tab">Trạng thái</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="blocked-tab" data-bs-toggle="tab" data-bs-target="#blocked" type="button"
                        role="tab">Chủ đề</button>
                </li>
            </ul>
            <div class="tab-content" id="reportTabContent">
                <div class="tab-pane fade show active" id="active" role="tabpanel">
                    <div style="max-width:1000px;" class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Avatar</th>
                                    <th>Người viết</th>
                                    <th>Email</th>
                                    <th>Nội dung</th>
                                    <th>Ngày viết</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($threads))
                                    @foreach ($threads as $index => $s)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td> <img src="{{ asset('storage/' . $s->user->avatar) }}" class="rounded-circle me-3"
                                                    width="60" height="60" alt="Avatar"></td>
                                            <td>{{ $s->user->name }}</td>
                                            <td>{{ $s->user->email }}</td>
                                            <td>{{ \Illuminate\Support\Str::limit(strip_tags($s->content), 100) }}</td>
                                            <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="#" class="btn btn-sm btn-success" data-bs-toggle="modal"
                                                    data-bs-target="#detailThreadModal-{{ $s->id }}">Chi
                                                    tiết</a>
                                                <button class="btn btn-sm btn-danger delete-thread"
                                                    data-id="{{ $s->id }}">Xoá</button>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="detailThreadModal-{{ $s->id }}" tabindex="-1" role="dialog"
                                            aria-labelledby="detailThreadModal" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Nội dung chi tiết</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>{{ $s->content }}</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">Hủy</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="pagination mt-3">
                                        {{ $threads->appends(request()->except('threads'))->links() }}
                                    </div>
                                @else
                                    <p>Chưa có dữ liệu</p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>


                <div class="tab-pane fade" id="blocked" role="tabpanel">
                    <div style="max-width:1000px;" class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Chủ đề</th>
                                    <th>Người viết</th>
                                    <th>Thẻ</th>
                                    <th>Nội dung</th>
                                    <th>Ngày viết</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($topics))
                                    @foreach($topics as $index => $s)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $s->title }}</td>
                                            <td> <a style="color:rgb(36, 33, 33)"
                                                    href="{{ route('admin.user.detail', ['user' => $s->user->id]) }}">{{ $s->user->name }}</a>
                                            </td>
                                            <td>{{ $s->tag->tag_name ?? null }}</td>
                                            <td>{{ $s->content }}</td>
                                            <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{route('admin.thread.detail-topic', ['topic' => $s->id])}}"
                                                    class="btn btn-sm
                                                                                                                                                                                                                                                                                        btn-success">Chi
                                                    tiết</a>
                                                <button class="btn btn-sm btn-danger delete-thread"
                                                    data-id="{{ $s->id }}">Xoá</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">Chưa có dữ liệu</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>


                    @if(isset($topics))
                        <div class="pagination mt-3">
                            {{ $topics->appends(request()->except('topics'))->links() }}
                        </div>
                    @endif
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {

                    document.querySelectorAll('.delete-thread').forEach(button => {
                        button.addEventListener('click', function (e) {
                            e.preventDefault();
                            const thread = this.dataset.id;
                            const auth_token = localStorage.getItem('auth_token');
                            if (confirm("Bạn có chắc muốn xóa bài viết này không này không?")) {
                                $.ajax({
                                    url: `/api/admin/thread/delete-thread/${thread}`,
                                    method: "DELETE",
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
            <script>
                // Khi người dùng click tab, lưu tab ID
                document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tab => {
                    tab.addEventListener('shown.bs.tab', function (e) {
                        localStorage.setItem('activeTab', e.target.id);
                    });
                });

                // Khi load lại trang, đặt lại tab đã lưu
                document.addEventListener('DOMContentLoaded', function () {
                    const activeTab = localStorage.getItem('activeTab');
                    if (activeTab) {
                        const tabTriggerEl = document.getElementById(activeTab);
                        if (tabTriggerEl) {
                            new bootstrap.Tab(tabTriggerEl).show();
                        }
                    }
                });
            </script>

@endsection