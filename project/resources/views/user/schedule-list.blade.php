@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <h3 class="mb-4">Danh sách bài viết hẹn lịch đăng</h3>
            <form id="filter" class="row g-3 mb-4 p-3 rounded bg-light">
                <div class="col-md-3">
                    <select name="status" class="form-select rounded-3">
                        <option value=""> Lọc theo trạng thái</option>
                        <option value="">Trạng thái</option>
                        <option value="0">Chưa đăng</option>
                        <option value="1">Đã đăng</option>
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
            @foreach ($posts as $p)
                @include('partials.blog', ['post' => $p])
            @endforeach

            <div class="pagination mt-3">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
@endsection