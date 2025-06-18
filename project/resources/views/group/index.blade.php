@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Nhóm cộng đồng <button class="btn" data-toggle="modal" data-target="#joinGroupModal">

                            <div>
                                <p style="color:rgb(84, 81, 238);"><i class="fa-solid fa-magnifying-glass"></i>Tìm nhóm</p>
                        </button></h2>
                    <a href="#" style="color:#fff;background-color:rgb(84, 81, 238);" class="btn " data-toggle="modal"
                        data-target="#createGroupModal">
                        Tạo nhóm
                    </a>
                </div>

                <div class="modal fade" id="joinGroupModal" tabindex="-1" aria-labelledby="joinGroupModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="join-group" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createGroup">Tìm nhóm
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Id:</label>
                                    <input type="text" name="id" id="id" class="form-control" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-upload"></i> Tìm
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="createGroupModal" tabindex="-1" aria-labelledby="createGroupModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form id="create-group" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-header">
                                    <h5 class="modal-title" id="createGroup">Tạo nhóm
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <label class="form-label">Tên nhóm:</label>
                                    <input type="text" name="name" id="id" class="form-control" required>
                                    <label class="form-label">Mô tả :</label>
                                    <input type="text" name="description" id="description" class="form-control" required>
                                    <label for="avatar" class="form-label">Chọn ảnh:</label>
                                    <input type="file" name="bgr" id="bgr" accept="image/*" class="form-control">
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="bi bi-upload"></i> Tạo
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


            @if (isset($groups))
                <div class="row">

                    @foreach ($groups as $gr)
                        <div class="col-md-4 mb-4">
                            <div class="group-card" style="background-color:#fff;">
                                <div class="group-banner"
                                    style="background-image: url('{{ asset('storage/' . $gr->bgr) }}'); background-size: cover; background-position: center; height: 180px; position: relative; border-radius: 10px;">
                                    <div class="overlay"
                                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); border-radius: 10px;">
                                    </div>
                                    <h4 class="group-name"
                                        style="position: absolute; bottom: 10px; left: 15px; color: white; z-index: 2;">
                                        {{ $gr->name }}
                                    </h4>
                                </div>
                                <div class="group-content mt-2 p-2">
                                    <p class="description">{{ $gr->description }}</p>

                                    <div class="meta  d-flex justify-content-between">
                                        <a id="detail-{{ $gr->id }}" href="#" class="btn btn-outline-primary btn-sm">Xem
                                            nhóm</a>

                                        @if ($gr->isAdmin(auth()->user()))
                                            <a href="#" class="btn btn-success btn-sm ms-auto"><i class="fa-solid fa-user-tie"></i></a>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endforeach
            @else
                    <h6>Chưa tham gia nhóm nào</h6>
                @endif
            </div>
        </div>
    </div>
    </div>
    </div>
    <script>
        document.querySelectorAll('.btn-outline-primary').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                let group = this.id.split('-')[1];
                let user = localStorage.getItem('user_id');
                if (user) {
                    window.location.href = '/group/detail/' + user + "/" + group;
                }
            });
        });
    </script>
    <script>
        document.getElementById('create-group').addEventListener('submit', function (e) {
            e.preventDefault();
            const form = document.getElementById('create-group')
            const formData = new FormData(form);
            let userId = localStorage.getItem('user_id');
            let auth_token = localStorage.getItem('auth_token');
            console.log(userId);
            $.ajax({
                url: `/api/group/create-group/${userId}`,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {

                    'Authorization': `Bearer ${auth_token}`
                },
                success: function (response) {

                    if (response.status == "success") {
                        alert(response.message);
                        location.reload();
                    } else {
                        console.log(response)
                        let errorMessages = '';
                        Object.values(response.message).forEach(fieldErrors => {
                            fieldErrors.forEach(err => {
                                errorMessages += err + '\n';
                            });
                        });
                        alert(errorMessages);
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);
                }
            });

        });
    </script>

    <script>
        document.getElementById('join-group').addEventListener('submit', function (e) {
            e.preventDefault();
            let id = document.getElementById('id').value;
            let auth_token = localStorage.getItem('auth_token');
            $.ajax({
                url: `/api/group/find`,
                method: "POST",
                data: {
                    id: id
                },
                headers: {
                    'Authorization': `Bearer ${auth_token}`
                },
                success: function (response) {

                    if (response.success == true) {
                        window.location.href = `/group/find-result/${response.group[0]['id']}`;
                    } else {
                        alert('Không tìm thấy nhóm!');
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);
                }
            });

        });
    </script>
@endsection