@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Thư mục của bạn <button class="btn" data-toggle="modal" data-target="#joinGroupModal">

                        </button></h2>

                    <a href="#" class="btn btn-success" data-toggle="modal" data-target="#createFolderModal">
                        Tạo mới
                    </a>

                    <div class="modal fade" id="createFolderModal" tabindex="-1" aria-labelledby="createFolderModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form id="create-folder" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="createGroup">Tạo thư mục
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Đóng">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <label class="form-label">Tên :</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                        <label for="tags">Chọn thẻ:</label>
                                        <select id="tag_id" name="tag_id" class="form-control">
                                            <option value="" disabled selected>Chọn thẻ</option>
                                            @foreach ($tags as $t)
                                                <option value="{{ $t->id }}">{{ $t->tag_name }}</option>
                                            @endforeach
                                        </select>
                                        <label class="form-label">Mô tả:</label>
                                        <input type="text" name="des" id="des" class="form-control" required>
                                        <label for="bgr" class="form-label">Chọn ảnh:</label>
                                        <input type="file" name="bgr" id="bgr" accept="image/*" class="form-control"
                                            required>
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


                @if (isset($folders))
                    <div class="row">

                        @foreach ($folders as $f)
                            <div class="col-md-4 mb-4">
                                <div class="folder-card shadow-sm rounded-4 border border-light">
                                    <div class="folder-header rounded-top-4 px-3 py-4 d-flex align-items-end"
                                        style="border-radius:10px;background-image: url('{{ asset('storage/' . $f->bgr) }}'); background-size: cover; background-position: center; height: 220px;">
                                        <h5 class="text-white mb-0 fw-bold">{{ $f->name }}</h5>
                                    </div>
                                    <div class="folder-body p-3 bg-white">
                                        <p class="text-muted mb-2">{{ $f->des }}</p>
                                        <div class="d-flex  justify-content-between">
                                            <a id="delete-{{ $f->id }}" href="#" class="btn btn-outline-danger btn-sm delete-btn">
                                                <i class="fa-solid fa-trash"></i>Xóa
                                            </a>
                                            <a id="detail-{{ $f->id }}" href="#" class="btn btn-outline-primary btn-sm detail-btn">
                                                <i class="fas fa-eye me-1"></i>Xem
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                @else
                        <h6>Chưa tạo thư mục nào!</h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        document.querySelectorAll('.detail-btn').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                let folderId = this.id.split('-')[1];
                let user = localStorage.getItem('user_id');
                if (user) {
                    window.location.href = `/folder/detail/${user}/${folderId}`;
                }
            });
        });

        document.querySelectorAll('.delete-btn').forEach(function (button) {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                let folderId = this.id.split('-')[1];
                let user = localStorage.getItem('user_id');
                if (confirm('Bạn chắc chắn muốn xóa thư mục này này?')) {
                    $.ajax({
                        url: `/api/folder/delete/${folderId}`,
                        method: "POST",
                        headers: {
                            'Authorization': `Bearer ${auth_token}`
                        },
                        success: function (response) {

                            if (response.success == true) {
                                alert(response.message);
                                location.reload();
                            } else {
                                alert(response.message);
                                location.reload();
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Error: ", error);
                        }
                    })
                }

            });
        });
    </script>
    <script>
        document.getElementById('create-folder').addEventListener('submit', function (e) {
            e.preventDefault();
            let userId = localStorage.getItem('user_id');
            let auth_token = localStorage.getItem('auth_token');
            const form = document.getElementById('create-folder')
            const formData = new FormData(form);
            $.ajax({
                url: `/api/folder/create-folder/${userId}`,
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {

                    'Authorization': `Bearer ${auth_token}`
                },
                success: function (response) {

                    if (response.status_code === 200) {
                        alert(response.message);
                        location.reload();
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error: ", error);
                }
            });

        });
    </script>

@endsection