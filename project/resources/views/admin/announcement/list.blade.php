@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="fw-bold"> Quản lý thông báo</h3>
                <button style="border-radius:25px;" class="btn btn-primary d-flex align-items-center shadow-sm"
                    data-bs-toggle="modal" data-bs-target="#createAnnounceModal">
                    <i class="fa-solid fa-plus me-2"></i> Tạo thông báo
                </button>
            </div>

            <ul class="nav nav-tabs rounded-3 overflow-hidden" id="announceTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                        type="button" role="tab">Đang hoạt động</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="blocked-tab" data-bs-toggle="tab" data-bs-target="#blocked" type="button"
                        role="tab">Đã ngừng</button>
                </li>
            </ul>

            <div class="tab-content mt-4" id="announceTabContent">

                <div class="tab-pane fade show active" id="active" role="tabpanel">
                    @forelse($active_announcements as $a)
                        <div style="border-radius:25px;" class="card shadow-sm border-0 mb-3">
                            <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-semibold text-primary mb-2">{{ $a->title }}</h5>
                                    <p class="mb-1">{{ $a->content }}</p>
                                    <small class="text-muted">Tạo lúc: {{ $a->created_at }}</small>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button style="margin-right:5px;" class="btn btn-outline-danger btn-sm block-btn"
                                        data-id="{{ $a->id }}">
                                        Ngừng kích hoạt
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $a->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Không có thông báo hoạt động.</p>
                    @endforelse

                    <div class="pagination mt-3">
                        {{ $active_announcements->links() }}
                    </div>
                </div>


                <div class="tab-pane fade" id="blocked" role="tabpanel">
                    @forelse($in_active_announcements as $a)
                        <div style="border-radius:25px;" class="card shadow-sm border-0 mb-3">
                            <div class="card-body d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="fw-semibold text-secondary mb-2">{{ $a->title }}</h5>
                                    <p class="mb-1">{{ $a->content }}</p>
                                    <small class="text-muted">Tạo lúc: {{ $a->created_at }}</small>
                                </div>
                                <div class="d-flex justify-content-end">
                                    <button style="margin-right:5px;" class="btn btn-outline-secondary btn-sm open-btn"
                                        data-id="{{ $a->id }}">
                                        Kích hoạt
                                    </button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="{{ $a->id }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Không có thông báo đã ngừng.</p>
                    @endforelse

                    <div class="pagination mt-3">
                        {{ $in_active_announcements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('modals.create-announce')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const auth_token = localStorage.getItem('auth_token');
            document.querySelectorAll('.block-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;

                    if (confirm("Bạn có chắc ngừng kích hoạt thông báo này không?")) {
                        $.ajax({
                            url: `/api/admin/annoucement/inactive/${id}`,
                            method: "GET",
                            headers: {

                                'Authorization': `Bearer ${auth_token}`
                            },
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi ngừng kích hoạt nhóm này!");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Lỗi:", error);
                            }
                        });
                    }
                });

            });

            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const id = this.dataset.id;
                    if (confirm("Bạn có chắc xóa thông báo này không?")) {
                        $.ajax({
                            url: `/api/admin/annoucement/delete/${id}`,
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
                                    alert("Có lỗi xảy ra khi xóa thông báo này!");
                                }
                            },
                            error: function (xhr, status, error) {
                                console.error("Lỗi:", error);
                            }
                        });
                    }
                });

            });

            document.querySelectorAll('.open-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const id = this.dataset.id;
                    if (confirm("Bạn có chắc muốn mở thông báo này không?")) {
                        $.ajax({
                            url: `/api/admin/annoucement/active/${id}`,
                            method: "GET",
                            headers: {

                                'Authorization': `Bearer ${auth_token}`
                            },
                            success: function (response) {
                                if (response.success == true) {
                                    alert(response.message);
                                    location.reload();
                                } else {
                                    alert("Có lỗi xảy ra khi mở thông báo này!");
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