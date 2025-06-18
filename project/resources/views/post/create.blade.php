@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/quill-image-uploader@1.2.0/dist/quill.imageUploader.min.js"></script>
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <a id="back" href="#" class="btn btn-outline-secondary mb-4">
                &laquo; Quay lại
            </a>
            <form id="createPost" method="Post" enctype="multipart/form-data">
                @csrf
                <label style="color:#007bff;font-size:26px; "><strong>Tạo bài mới</strong></label>
                <div class="form-group">
                    <label for="title"><strong>Tựa bài viết:</strong></label>
                    <input type="text" name="title" class="form-control" placeholder="Enter title" required>
                </div>
                <div class="form-group">
                    <label for="tags"><strong>Thẻ:</strong></label>
                    <select name="tag_id" class="form-control">
                        <option value="" disabled selected>Chọn</option>
                        @foreach($tags as $t)
                            <option value="{{$t->id}}">{{$t->tag_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="folders"><strong>Thư mục:</strong></label>
                    <select name="folder_id" class="form-control">
                        <option value="" disabled selected>Chọn</option>
                        @foreach($folders as $t)
                            <option value="{{$t->id}}">{{$t->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title"><strong>Mô tả</strong></label>
                    <textarea type="text" rows="4" name="des" class="form-control" placeholder="Nhập mô tả..."
                      ></textarea>
                </div>
                <div class="form-group">
                    <label><strong>Nội dung:</strong></label>
                    <div id="editor" class="mb-3" style="height: 300px;"></div>
                    <textarea rows="3" class="mb-3 d-none" name="content" id="content"></textarea>
                </div>


                <div class="form-group">
                    <label><strong>Trạng thái:</strong></label>
                    <select style="   padding: 0.5rem 0.75rem;margin-left:10px;width:265px;  border-radius: 6px;"
                        id="status" name="status">
                        <option value="drafted">Nháp</option>
                        <option value="published">Xuất bản</option>
                    </select>
                </div>
                <div class="form-group" style="max-width: 350px;">
                    <label for="scheduled_at" style="margin-bottom: 0.5rem; color: #333;">
                        <strong>Thời gian hẹn đăng:</strong>
                    </label>
                    <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control" style="
                                                                                                width: 100%;
                                                                                                font-size: 1rem;
                                                                                                border: 1.5px solid #ccc;
                                                                                                border-radius: 6px;
                                                                                                transition: border-color 0.3s ease;
                                                                                            " placeholder="Chọn thời gian">
                    <small class="text-muted" style="display: block; margin-top: 0.25rem; font-style: italic;">
                        Bỏ trống nếu muốn đăng ngay
                    </small>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Lưu</button>
            </form>
        </div>


    </div>
    </div>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script>
        document.getElementById('back').addEventListener('click', function (e) {
            e.preventDefault();
            window.history.back();
        });
    </script>
    <script>

        const quill = new Quill("#editor", {
            theme: "snow",
            modules: {
                toolbar: {
                    container: [
                        [{ header: [1, 2, false] }],
                        ["bold", "italic", "underline"],
                        ["image", "code-block"]
                    ],
                    handlers: {
                        image: function () {
                            var input = document.createElement('input');
                            input.setAttribute('type', 'file');
                            input.setAttribute('accept', 'image/*');
                            input.click();
                            input.onchange = async function () {
                                var file = input.files[0];
                                var formData = new FormData();
                                formData.append("image", file);
                                try {
                                    let response = await fetch("/post/save-img", {
                                        method: "POST",
                                        body: formData,
                                        headers: {
                                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                                        }
                                    });

                                    let result = await response.json();
                                    if (result.url) {
                                        let range = quill.getSelection();
                                        quill.insertEmbed(range.index, "image", result.url);
                                    } else {
                                        alert("Có lỗi xảy ra khi tải ảnh lên!");
                                    }
                                } catch (error) {
                                    alert("Error uploading image!");
                                }
                            };
                        }
                    }
                }
            }
        });

        quill.on('text-change', function () {
            document.getElementById("content").value = quill.root.innerHTML;
        });
        document.getElementById('createPost').addEventListener('submit', function (e) {
            e.preventDefault();
            document.querySelector("#content").value = quill.root.innerHTML;

            const form = document.querySelector('#createPost');
            const formData = new FormData(form);
            let id = localStorage.getItem('user_id');
            let auth_token = localStorage.getItem('auth_token');

            fetch(`/api/post/save-post/${id}`, {
                method: "POST",
                headers: {
                    'x-csrf-token': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Authorization': `Bearer ${auth_token}`
                },
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '/post/detail-post/' + data.id;
                    } else {
                        alert("Tạo bài viết không thành công!");
                    }
                })
                .catch(error => alert('Error:' + error));
        });
    </script>

@endsection