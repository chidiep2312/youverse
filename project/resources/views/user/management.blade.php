@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Chia sẻ trạng thái- chủ đề</h3>
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
                                    <th>Nội dung</th>
                                    <th>Ngày viết</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($threads))
                                    @foreach($threads as $index => $s)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $s->content }}</td>
                                            <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
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
                    @if(isset($threads))
                        <div class="pagination mt-3">
                            {{ $threads->appends(request()->except('threads'))->links() }}
                        </div>
                    @endif
                </div>


                <div class="tab-pane fade" id="blocked" role="tabpanel">
                    <div style="max-width:1000px;" class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Chủ đề</th>
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
                                            <td>{!!Illuminate\Support\Str::limit(strip_tags($s->title), 30) !!}</td>
                                            <td>{{ $s->tag->tag_name ?? null }}</td>
                                            <td>{!!Illuminate\Support\Str::limit(strip_tags($s->content), 50) !!}</td>
                                            <td>{{ $s->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{route('thread.detail-topic', ['topic' => $s->id])}}"
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
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.delete-post').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const post = this.dataset.id;
                    if (confirm("Bạn có chắc muốn xóa bài viết này không?")) {
                        $.ajax({
                            url: `/api/post/delete-post/${post}`,
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

            document.querySelectorAll('.delete-thread').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const thread = this.dataset.id;
                    if (confirm("Bạn có chắc muốn xóa trạng thái này không?")) {
                        $.ajax({
                            url: `/api/thread/delete-thread/${thread}`,
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

@endsection