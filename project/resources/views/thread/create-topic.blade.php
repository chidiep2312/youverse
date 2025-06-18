@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/admin/user.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="short-post-list">
                <div class="short-post-card">
                    <div class="short-post-header">
                        <div class="user-info" style="margin:5px;">
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="rounded-circle shadow-sm"
                                width="48" height="48" style="object-fit: cover; border: 2px solid #fff;">
                            <strong style="color:#007bff;">{{ auth()->user()->name }}</strong>

                        </div>
                    </div>
                    <form style="margin-top:5px;" id="createTopic">
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="Nhập chủ đề..." required>
                        </div>
                        <input type="hidden" name="user_id" value="{{ auth()->id() }}">
                        <input type="hidden" name="type" value="topic">

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
                            <label><strong>Nội dung:</strong></label>
                            <div id="editor" class="mb-3" style="height: 300px;"></div>
                            <textarea rows="3" class="mb-3 d-none" name="content" id="content"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Đăng </button>
                    </form>
                </div>
            </div>
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

        quill.on('text-change', function () {
            document.getElementById("content").value = quill.root.innerHTML;
        });
        document.getElementById('createTopic').addEventListener('submit', function (e) {
            e.preventDefault();
            document.querySelector("#content").value = quill.root.innerHTML;

            const form = document.querySelector('#createTopic');
            const formData = new FormData(form);
            let id = localStorage.getItem('user_id');
            let auth_token = localStorage.getItem('auth_token');

            fetch(`/api/thread/save-topic`, {
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

                        window.location.href = '/thread/topic/detail/' + data.id;
                    } else {
                        alert(data.error);
                    }
                })
                .catch(error => alert('Error:' + error));
        });
    </script>
@endsection