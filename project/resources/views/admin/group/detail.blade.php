@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">

                <div class="group-info-card rounded shadow-lg p-4 mb-4 position-relative">
                    <div class="position-absolute top-0 start-0 end-0 bg-gradient"
                        style="height: 20px; border-radius: 10px 10px 0 0;"></div>
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 style="color:rgb(6, 110, 20);" class="mb-3">{{ $group->name }}</h2>
                            <p class="lead text-black">{{ $group->description }}</p>
                            <p><strong>Người tạo:</strong> {{ $group->creator->name ?? 'Không rõ' }}</p>
                            <p><strong>Thành viên:</strong> {{ $group->member_count }}</p>
                            <p><strong>Tạo lúc:</strong> {{ $group->created_at }}</p>
                        </div>
                        <div class="col-md-4 text-end">
                            @if ($group->is_active == true)
                                <button value="{{$group->id}}" class="btn inactive">
                                    <p style="font-size:20px;">🔐 Khóa</p>
                                </button>
                            @else
                                <button value="{{$group->id}}" class="btn active ">
                                    <p style="font-size:20px;"> 🔑 Mở</p>
                                </button>
                            @endif
                            <div class="back-link">
                                <a href="#" id="back"> ← Quay lại</a>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="card shadow-sm p-4 mb-5 bg-light">
                    <h4 class="text-success mb-4">👥 Danh sách thành viên nhóm</h4>
                    @if(isset($members))
                        <div class="table-responsive" style="max-height: 350px; overflow-y: auto;">
                            <table class="table table-bordered align-middle">
                                <thead class="table-light sticky-top bg-white" style="top: 0;">
                                    <tr>
                                        <th></th>
                                        <th>Ảnh đại diện</th>
                                        <th>Tên</th>
                                        <th>Email</th>
                                        <th>Ngày tham gia</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($members as $index => $member)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <img src="{{asset('storage/' . $member->avatar) }}" width="40" height="40"
                                                    class="rounded-circle" alt="avatar">
                                            </td>
                                            <td>{{ $member->name }}</td>
                                            <td>{{ $member->email }}</td>
                                            <td>{{ $member->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">Không có thành viên nào trong nhóm.</p>
                    @endif
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="text-primary mb-3">Các bài viết gần đây</h3>
                        @if (isset($posts))
                            <div class="posts-section">
                                <div class="posts-list">
                                    @foreach ($posts as $post)
                                        @include('partials.blog', ['post' => $post])
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <h3>Chưa có bài viết!</h3>
                        @endif
                    </div>
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