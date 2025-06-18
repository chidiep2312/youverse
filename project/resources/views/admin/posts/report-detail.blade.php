@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/detail-post.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="d-flex justify-content-between align-items-center mb-4">

                <a id="back" href="#" class="btn btn-link">
                    &laquo; Quay lại
                </a>


                <div>
                    <button id="delete-post" class="btn btn-danger me-2">
                        Xóa
                    </button>
                    <button id="done-report" class="btn btn-success">
                        Duyệt
                    </button>
                </div>
            </div>


            <div class="post-detail card p-4 shadow-sm rounded-4 bg-white">

                <h1 class="post-title text-primary fw-bold" style="font-size: 2.5rem;">
                    {{ $post->title }}
                </h1>

                <div class="dropdown" style="float:right;">

                </div>
                <div class="post-meta d-flex flex-wrap gap-3 mb-4 text-secondary" style="font-size: 0.9rem;">
                    <span><strong class="text-primary">Thẻ:</strong> {{ $post->tag->tag_name }}</span>
                    <span><strong class="text-primary">Tạo lúc:</strong> {{ $post->created_at->format('d/m/Y H:i') }}</span>
                    <span><strong class="text-primary">Người viết:</strong> {{ $post->user->name }}</span>
                    <span><i class="fa-solid fa-eye"></i> {{ $post->view_count }}</span>
                    <span><i class="fa-regular fa-heart"></i> {{ $likesCount }}</span>
                </div>

                <div class="post-content">
                    <br>
                    {!! $post->content !!}
                </div>


                <div class="reporter" style="max-height:300px;overflow-y: auto;">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>STT</th>
                                <th>ID</th>
                                <th>Người báo cáo</th>
                                <th>Lý do</th>
                                <th>Khác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($reporters->count() > 0)
                                @foreach ($reporters as $index => $reporter)
                                    <tr>

                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $reporter->user->id ?? 'Không xác định' }}</td>
                                        <td><a style="color:black"
                                                href="{{ route('admin.user.detail', ['user' => $reporter->user->id]) }} ?? '#'">{{ $reporter->user->name ?? 'Không xác định' }}</a>
                                        </td>
                                        <td>{{ $reporter->reason ?? 'Không xác định' }}</td>
                                        <td>{{ $reporter->details ?? 'Không' }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div>

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
        const report = {{ $report->id }};
        document.addEventListener('DOMContentLoaded', function () {
            const auth_token = localStorage.getItem('auth_token');

            document.getElementById('delete-post').addEventListener('click', function (e) {
                e.preventDefault();

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
            document.getElementById('done-report').addEventListener('click', function (e) {

                e.preventDefault();

                $.ajax({
                    url: `/api/admin/post/done-report/${report}`,
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.success == true) {
                            alert("Đã duyệt!");
                            window.history.back();
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
    </script>

@endsection