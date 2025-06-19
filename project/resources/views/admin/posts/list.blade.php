@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4"><i class="bi bi-flag-fill text-danger"></i> Danh sách bài viết bị báo cáo</h3>

            <ul class="nav nav-tabs" id="reportTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active"
                        type="button" role="tab">Chưa xử lý</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="blocked-tab" data-bs-toggle="tab" data-bs-target="#blocked" type="button"
                        role="tab">Đã xử lý</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="reportTabContent">
                <div class="tab-pane fade show active" id="active" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề</th>
                                    <th>Người viết</th>
                                    <th>Lý do báo cáo</th>
                                    <th>Người báo cáo</th>
                                    <th>Ngày báo cáo</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($reports))
                                    @foreach($reports as $index => $report)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{  $report->post->title }}</td>
                                            <td><a style="color:rgb(36, 33, 33)"
                                                    href="{{ route('admin.user.detail', ['user' => $report->post->user->id]) }}">{{  $report->post->user->name }}</a>
                                            </td>

                                            <td>{{  $report->reason  }}
                                                @if($report->details)
                                                    - {{ $report->details }}
                                                @endif
                                            </td>
                                            <td>{{ $report->user->name }}</td>
                                            <td>{{ $report->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.post.detail-report', ['report' => $report->id]) }}"
                                                    class="btn btn-sm btn-primary">Xem</a>
                                                <button data-id="{{ $report->id }}" class="btn btn-sm btn-sucess done">Đã xử
                                                    lý</button>

                                                <button data-id="{{ $report->id }}"
                                                    class="btn btn-sm btn-danger delete-post">Xoá</button>

                                            </td>
                                        </tr>
                                    @endforeach

                                @endif
                            </tbody>
                        </table>
                        <div class="pagination mt-3">
                            {{ $reports->appends(request()->except('reports'))->links() }}

                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="blocked" role="tabpanel">
                    <div class="table-responsive">

                        @if(isset($solved))
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>STT</th>
                                        <th>Tiêu đề</th>
                                        <th>Người viết</th>
                                        <th>Lý do báo cáo</th>
                                        <th>Người báo cáo</th>
                                        <th>Ngày báo cáo</th>
                                        <th class="text-center">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($solved as $index => $s)
                                        <tr>
                                            <td>{{ $index + 1  }}</td>
                                            <td>{{  $s->post->title }}</td>
                                            <td>{{  $s->post->user->name }}</td>
                                            <td>{{  $s->reason  }}
                                                @if($s->details)
                                                    - {{ $s->details }}
                                                @endif
                                            </td>
                                            <td>{{ $s->user->name }}</td>
                                            <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.post.detail-report', ['report' => $s->id]) }}"
                                                    class="btn btn-sm btn-primary">Xem</a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    <div class="pagination mt-3">
                                        {{$solved->appends(request()->except('solved'))->links() }}

                                    </div>
                        @else
                                            <p>Chưa có dữ liệu</p>

                                        </tbody>
                                    </table>
                                @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            const auth_token = localStorage.getItem('auth_token');
            document.querySelectorAll('.delete-post').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const report = this.dataset.id;
                    if (confirm("Bạn có chắc muốn xóa bài viết này không này không?")) {
                        $.ajax({
                            url: `/api/admin/post/delete-report/${report}`,
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

            document.querySelectorAll('.done').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const report = this.dataset.id;
                    $.ajax({
                        url: `/api/admin/post/done-report/${report}`,
                        method: "POST",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (response) {
                            if (response.success == true) {
                                alert("Đánh dấu đã hoàn thành!");
                                location.reload();
                            } else {
                                alert("Có lỗi xảy ra khi xóa.");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Lỗi:", error);
                        }
                    });
                });
            });

        });



    </script>
@endsection