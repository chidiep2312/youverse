@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Danh sách bài viết</h3>
            <ul class="nav nav-tabs mb-4" id="postTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="grid-tab" data-bs-toggle="tab" data-bs-target="#grid" type="button"
                        role="tab">Lưới</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table" type="button"
                        role="tab">Bảng</button>
                </li>

            </ul>
            <form id="filter" class="row g-3 mb-4 p-3 rounded bg-light">
                <div class="col-md-3">
                    <input type="text" name="title" value="{{ request('title') }}" class="form-control rounded-3"
                        placeholder=" Tìm theo tiêu đề">
                </div>

                <div class="col-md-3">
                    <select name="tag_id" class="form-select mb-2 rounded-3">
                        <option value=""> Lọc theo thẻ</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" {{ request('tag_id') == $tag->id ? 'selected' : '' }}>
                                {{ $tag->tag_name }}
                            </option>
                        @endforeach
                    </select>

                    <select name="group_id" class="form-select rounded-3">
                        <option value="">Lọc theo nhóm</option>
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>
                                {{ $group->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <select name="status" class="form-select rounded-3">
                        <option value=""> Lọc theo trạng thái</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>
                            Đã xuất bản
                        </option>
                        <option value="drafted" {{ request('status') == 'drafted' ? 'selected' : '' }}>
                            Bản nháp
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-center gap-2">
                    <button type="submit" class="btn btn-primary rounded-circle" title="Tìm kiếm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    <a href="{{ route('post.my-posts') }}" class="btn btn-secondary rounded-circle" title="Làm mới">
                        <i class="fa-solid fa-arrows-rotate"></i>
                    </a>
                </div>
            </form>


            <div class="tab-content" id="postTabsContent">
                <div class="tab-pane fade show active" id="grid" role="tabpanel">

                    <div class="container">
                        <div class="row">
                            @foreach ($posts as $p)
                                @include('partials.blog', ['post' => $p])
                            @endforeach
                        </div>
                    </div>

                    <div class="pagination mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
                <div class="tab-pane fade" id="table" role="tabpanel">
                    <div style="max-width:1000px;" class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>STT</th>
                                    <th>Tiêu đề</th>
                                    <th>Thẻ</th>
                                    <th>Mô tả</th>
                                    <th>Ngày viết</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (isset($posts))
                                    @foreach ($posts as $index => $post)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $post->title }}</td>
                                            <td>{{ $post->tag->tag_name }}</td>

                                            <td>{!! \Illuminate\Support\Str::limit(strip_tags($post->des, 100)) !!}</td>
                                            <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                            <td class="text-center">
                                                <a href="{{ route('post.detail', ['post' => $post->id]) }}"
                                                    class="btn btn-sm btn-primary">Xem</a>
                                                <button data-id="{{ $post->id }}"
                                                    class="btn btn-sm btn-danger delete-post">Xoá</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        @if (isset($posts))
                            <div class="pagination mt-3">
                                {{ $posts->appends(request()->except('posts'))->links() }}
                            </div>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.delete-post').forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const postId = this.getAttribute('data-id');
                let id = localStorage.getItem('user_id');
                let confirmDelete = confirm('Bạn có chắc muốn xóa?');
                if (!confirmDelete) return;
                fetch(`/api/post/delete-post/${postId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Authorization': `Bearer ${auth_token}`
                    },
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success == true) {
                            alert(data.message);
                            window.location.href = '/post/my-posts';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Error:', error));

            });
        });
    </script>
@endsection