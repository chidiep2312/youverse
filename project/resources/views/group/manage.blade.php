@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel w-100">
        <div class="content-wrapper rounded-4">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Quản lý nhóm {{ $group->name }}</h2>
                </div>
                <a id="back" href="#" class="btn btn-outline-secondary mb-4">
                    &laquo; Quay lại
                </a>
                <ul class="nav nav-tabs mb-3" id="groupTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="members-tab" data-bs-toggle="tab" data-bs-target="#members"
                            type="button" role="tab">
                            Danh sách thành viên
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="requests-tab" data-bs-toggle="tab" data-bs-target="#requests"
                            type="button" role="tab">
                            Duyệt yêu cầu
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="invite-tab" data-bs-toggle="tab" data-bs-target="#invite" type="button"
                            role="tab">
                            Mời thành viên
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="update-tab" data-bs-toggle="tab" data-bs-target="#update" type="button"
                            role="tab">
                            Cập nhật nhóm
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="groupTabsContent">
                    <input type="hidden" value="{{ $group->id }}" id="group_id">


                    <div class="tab-pane fade show active" id="members" role="tabpanel">
                        @if ($group->users->isEmpty())
                            <p class="text-muted">Nhóm chưa có thành viên nào.</p>
                        @else
                            <div class="table-responsive " style="max-width: 1100px;">
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">STT</th>
                                            <th style="width: 80px;">Avatar</th>
                                            <th>Họ tên</th>
                                            <th>Email</th>
                                            <th>Ngày tham gia</th>
                                            <th class="text-center" style="width: 120px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($members as $index => $member)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $member->avatar) }}" alt="avatar"
                                                        class="rounded-circle" width="40" height="40">
                                                </td>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->email }}</td>
                                                <td>{{ \Carbon\Carbon::parse($member->pivot->created_at)->format('d/m/Y') }}</td>
                                                <td class="text-center">
                                                    @if ($group->isAdmin($member))
                                                        <button class="btn btn-outline-success btn-sm">
                                                            <i class="fa-solid fa-user-tie"></i>
                                                        </button>
                                                    @else
                                                        <button value="{{ $member->id }}" class="btn btn-outline-danger btn-sm remove">
                                                            <i class="fa-solid fa-user-xmark"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>


                    <div class="tab-pane fade" id="requests" role="tabpanel">
                        <div class="table-responsive" style="max-width: 1100px;">
                            @if ($join_requests->isEmpty())
                                <p>Không có yêu cầu nào.</p>
                            @else
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">STT</th>
                                            <th style="width: 80px;">Avatar</th>
                                            <th>Họ tên</th>
                                            <th>Email</th>
                                            <th class="text-center" style="width: 120px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($join_requests as $index => $request)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $request->avatar) }}" alt="avatar"
                                                        class="rounded-circle" width="40" height="40">
                                                </td>
                                                <td>{{ $request->name }}</td>
                                                <td>{{ $request->email }}</td>
                                                <td class="text-center">
                                                    <button value="{{ $request->id }}" class="btn btn-success btn-sm accept">Chấp
                                                        nhận</button>
                                                    <button value="{{ $request->id }}" class="btn btn-danger btn-sm reject">Từ
                                                        chối</button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>


                    <div class="tab-pane fade" id="invite" role="tabpanel">
                        <div class="table-responsive" style="max-width: 1100px;">
                            <form id="invite_user" class="d-flex align-items-center mb-3">
                                @csrf
                                <input type="text" name="email" id="email" class="form-control me-2"
                                    placeholder="Nhập email...">
                                <button type="submit" class="btn btn-primary"><i
                                        class="fa-solid fa-paper-plane"></i></button>
                            </form>

                            @if ($invite_members->isEmpty())
                                <p>Không có người dùng.</p>
                            @else
                                <table class="table table-bordered table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 60px;">STT</th>
                                            <th style="width: 80px;">Avatar</th>
                                            <th>Họ tên</th>
                                            <th>Email</th>
                                            <th class="text-center" style="width: 120px;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invite_members as $index => $member)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $member->avatar) }}" alt="avatar"
                                                        class="rounded-circle" width="40" height="40">
                                                </td>
                                                <td>{{ $member->name }}</td>
                                                <td>{{ $member->email }}</td>
                                                <td class="text-center">
                                                    @if($group->users->contains($member))
                                                        <button class="btn btn-success btn-sm">Thành viên</button>
                                                    @else
                                                        <button class="btn btn-danger btn-sm invite"
                                                            value="{{ $member->id }}">Mời</button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>

                    <div class="tab-pane fade" id="update" role="tabpanel">

                        <form id="update_group" class="mb-4" enctype="multipart/form-data">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Tên nhóm</label>
                                    <input type="text" value="{{ $group->name }}" name="name" id="name" class="form-control"
                                        placeholder="Nhập tên nhóm">
                                </div>
                                <div class="col-md-6">
                                    <label for="des" class="form-label">Mô tả</label>
                                    <input type="text" value="{{ $group->description }}" name="description" id="des"
                                        class="form-control" placeholder="Mô tả">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bgr" class="form-label">Ảnh nền nhóm</label>
                                <input type="file" name="bgr" id="bgr" accept="image/*" class="form-control">
                            </div>

                            <div class="text-end">
                                <input type="hidden" name="member_count" value="{{ $group->member_count }}">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>




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
        const group = document.getElementById('group_id').value;
        document.querySelectorAll('.accept').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.value;
                const auth_token = localStorage.getItem('auth_token');

                $.ajax({
                    url: `/api/group/approve/${group}`,
                    method: "POST",
                    data: {
                        user_id: userId
                    },
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.success == true) {

                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                });
            });
        });

        document.querySelectorAll('.reject').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.value;
                const auth_token = localStorage.getItem('auth_token');
                $.ajax({
                    url: `/api/group/reject/${group}`,
                    method: "POST",
                    data: {
                        user_id: userId
                    },
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.success == true) {
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                });
            });
        });
        document.querySelectorAll('.remove').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.value;
                const auth_token = localStorage.getItem('auth_token');
                $.ajax({
                    url: `/api/group/remove/${group}`,
                    method: "POST",
                    data: {
                        user_id: userId
                    },
                    headers: {
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.success == true) {
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                });
            });
        });
        document.querySelectorAll('.invite').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const userId = this.value;
                const auth_token = localStorage.getItem('auth_token');
                $.ajax({
                    url: `/api/group/invite/${userId}`,
                    method: "POST",
                    data: {
                        group_id: group
                    },
                    headers: {
                        'x-csrf-token': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.success == true) {
                            alert('Đã gửi lời mời đến ' + response.user['name'])
                        } else {
                            alert('Có lỗi xảy ra!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);

                    }
                });
            });
        });
        document.getElementById('invite_user').addEventListener('submit', function (e) {
            e.preventDefault();
            const email = document.getElementById('email').value;
            let auth_token = localStorage.getItem('auth_token');
            $.ajax({
                url: '/api/group/invite',
                method: 'POST',
                headers: {
                    'x-csrf-token': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${auth_token}`
                },
                data: {
                    email: email,
                    group_id: group
                },
                success: function (response) {
                    if (response.success == true) {
                        alert('Đã gửi lời mời đến ' + response.user['name'])
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);

                }

            })
        })
        document.getElementById('update_group').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = document.getElementById('update_group')
            const formData = new FormData(form);
            let auth_token = localStorage.getItem('auth_token');
            $.ajax({
                url: `/api/group/update/${group}`,
                method: 'POST',
                headers: {
                    'x-csrf-token': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${auth_token}`
                },
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    if (response.success == true) {
                        alert('Cập nhật nhóm thành công');
                        location.reload();
                    } else {
                        alert('Có lỗi xảy ra!');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);

                }

            })
        })
    </script>

@endsection