@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <form id="editPost" method="Post" enctype="multipart/form-data">
                @csrf
                <label style="color:#28a745;font-size:26px; "><strong>Chỉnh sửa bài viết</strong></label>
                <div class="form-group">
                    <label for="title"><strong>Tựa đề</strong></label>
                    <input type="text" name="title" class="form-control" value="{{old('title', $post->title)}}" required>
                    <input type="hidden" name="group_id" class="form-control" value="{{old('group_id', $post->group_id)}}">
                </div>

                <div class="form-group">
                    <label for="tags"><strong>Thẻ:</strong></label>
                    <select name="tag_id" class="form-control">
                        <option value="" disabled selected>
                            {{ $post->tag->tag_name ?? 'Chọn thẻ' }}
                        </option>
                        @foreach($tags as $t)
                            <option value="{{ $t->id }}" {{ old('tag_id', $post->tag_id) == $t->id ? 'selected' : '' }}>
                                {{ $t->tag_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="folders"><strong>Thư mục:</strong></label>
                    <select name="folder_id" class="form-control">
                        <option value="" disabled selected>Chọn</option>
                        @foreach($folders as $t)
                            <option value="{{$t->id}}" {{ old('folder_id', $post->folder_id) == $t->id ? 'selected' : '' }}>
                                {{$t->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="title"><strong>Mô tả</strong></label>
                    <input type="text" name="des" class="form-control" value="{{old('des', $post->des)}}"
                        placeholder="Nhập mô tả...">

                </div>

                <div class="form-group">
                    <label for="content"><strong>Nội dung:</strong></label>
                    <div id="editor" class="border"></div>
                    <input type="hidden" name="content" id="content" value="{{old('content', $post->content)}}" required>
                </div>
                <div class="form-group">
                    <label>Status:</label>
                    <select id="status" name="status">
                        <option value="drafted">Nháp</option>
                        <option value="published">Xuất bản</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="scheduled_at"><strong>Thời gian hẹn đăng:</strong></label>
                    <input type="datetime-local" name="scheduled_at" class="form-control">
                    <small class="text-muted">Bỏ trống nếu muốn đăng ngay</small>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Cập nhật</button>
            </form>

        </div>

    </div>
    </div>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
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
                                        alert("Upload fail!");
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
        quill.root.style.height = '400px';
        document.getElementById('editPost').addEventListener('submit', function (e) {
            e.preventDefault();

            document.querySelector('#content').value = quill.root.innerHTML;
            const form = document.querySelector('#editPost');
            const formData = new FormData(form);
            let auth_token = localStorage.getItem('auth_token');
            let user_id = localStorage.getItem('user_id');
            const postId = "{{ $post->id }}";
            fetch(`/api/post/update-post/${postId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
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
                        alert(data.error);
                    }
                })
                .catch(error => {

                    alert('Error:' + error);
                });
        });
    </script>
@endsection