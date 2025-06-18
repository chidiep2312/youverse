@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <div style=" background-image: url('{{ asset('storage/' . $folder->bgr) ?? asset('assets/images/default.png')}}');  background-size: cover; background-position: center;"
                    class="folder-info-card rounded shadow-lg p-4 mb-4 position-relative">
                    <div class="row align-items-center">

                        <div class="col-md-8">
                            <input type="hidden" value="{{ $folder->id }}" id="folder">
                            <h2 style="color:rgb(88, 66, 187);" class="mb-3">{{ $folder->name }}</h2>
                            <p class="lead text-black">{{ $folder->des }}</p>
                            <p><strong>Tạo lúc:</strong> {{ $folder->created_at }}</p>
                        </div>

                    </div>
                </div>


                <div class="row">
                    <div class="col-12 ">
                        <h3 class="text-primary mb-3 text-center">Các bài viết gần đây</h3>
                        @if (isset($posts))
                            <div class="row">
                                @foreach ($posts as $post)
                                    @include('partials.blog', ['post' => $post])
                                @endforeach
                            </div>

                            <div class="pagination mt-3">
                                {{ $posts->links() }}
                            </div>
                        @else
                            <h3>Không có bài viết!</h3>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>


        document.getElementById('delete').addEventListener('submit', function (e) {
            e.preventDefault();
            let folder = document.getElementById('folder').value;
            if (confirm('Bạn chắc chắn muốn thư mục này?')) {
                $.ajax({
                    url: `/api/group/delete/${folder}`,
                    method: 'POST',
                    data: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    success: function (res) {
                        alert(res.message);
                        window.location.href = "/folder/";
                    },
                    error: function (err) {
                        alert('Xóa thư mục thất bại.');
                        console.log(err.responseJSON);
                    }
                });
            }
        });
                                                                                                    });
    </script>

@endsection