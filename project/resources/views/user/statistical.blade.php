@extends('layout.blog')

@section('content')

    <link rel="stylesheet" href="{{ asset('assets/css/statistic.css') }}">

    <style>

    </style>
    </head>

    <body>
        <div class="main-panel" style="width:100%;">
            <div class="content-wrapper" style="border-radius:25px;">
                <div class="container">
                    <h2 class="mb-4">Thống kê</h2>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="card text-bg-light">
                                <div class="card-body">
                                    <h6>Tổng số bài viết</h6>
                                    <div class="stats-number"><span id="posts">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-bg-light">
                                <div class="card-body">
                                    <h6>Bài viết nháp</h6>
                                    <div class="stats-number"><span id="drafteds">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-bg-light">
                                <div class="card-body">
                                    <h6>Tổng lượt xem</h6>
                                    <div class="stats-number"><span id="views">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-bg-light">
                                <div class="card-body">
                                    <h6>Tổng lượt thích</h6>
                                    <div class="stats-number"><span id="likes">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Lượt bình luận đã viết</h6>
                                    <div class="stats-number"><span id="comments">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Người theo dõi bạn</h6>
                                    <div class="stats-number"><span id="followers">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6>Bạn đang theo dõi</h6>
                                    <div class="stats-number"><span id="follows">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Số lượt báo cáo đã gửi</h6>
                                    <div class="stats-number"><span id="reports">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h6 class="mb-3">Nhóm đã tham gia</h6>
                                    <div class="stats-number"><span id="groups">Đang tính...</span></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="card mb-4">
                        <div class="card-body">
                            <h5> Biểu đồ số lượng bài viết theo tháng</h5>
                            <div class="chart-container">
                                <canvas id="postsChart" style="width:100%;height:400px"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="color:darkblue;"> <strong>Top 5 bài viết nhiều like</strong></h5>
                                    <ol>
                                        @if(isset($mostLike))
                                            @foreach($mostLike as $p)
                                                <li><strong>{{ $p->title }}</strong> - {{ $p->like_count }} likes</li>
                                            @endforeach
                                        @else
                                            <p>Không có dữ liệu</p>
                                        @endif
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-body">
                                    <h5 style="color:darkblue;"><strong>Top 5 bài viết nhiều lượt xem</strong></h5>
                                    <ol>
                                        @if(isset($mostView))
                                            @foreach($mostView as $p)
                                                <li><strong>{{ $p->title }}</strong> - {{ $p->like_count }} views</li>
                                            @endforeach
                                        @else
                                            <p>Không có dữ liệu</p>
                                        @endif
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>
        $(document).ready(function () {

            let posts = document.getElementById('posts');
            let drafteds = document.getElementById('drafteds');
            let views = document.getElementById('views');
            let likes = document.getElementById('likes');
            let comments = document.getElementById('comments');
            let followers = document.getElementById('followers');
            let follows = document.getElementById('follows');
            let reports = document.getElementById('reports');
            let groups = document.getElementById('groups');
            const user = localStorage.getItem('user_id');
            $.ajax({
                url: `/statistic/statistic/${user}`,
                method: "GET",
                success: function (response) {

                    if (response.status == 'success') {
                        posts.textContent = response.data.posts;
                        drafteds.textContent = response.data.drafted;
                        views.textContent = response.data.views;
                        likes.textContent = response.data.likes;
                        comments.textContent = response.data.comments;
                        followers.textContent = response.data.followers;
                        follows.textContent = response.data.follows;
                        reports.textContent = response.data.reports;
                        groups.textContent = response.data.groups;
                    } else {
                        posts.textContent = 'Lỗi';
                        drafteds.textContent = 'Lỗi';
                        views.textContent = 'Lỗi';
                        likes.textContent = 'Lỗi';
                        comments.textContent = 'Lỗi';
                        followers.textContent = 'Lỗi';
                        follows.textContent = 'Lỗi';
                        reports.textContent = 'Lỗi';
                        groups.textContent = 'Lỗi';
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });

            $.ajax({
                url: `/statistic/linechart/${user}`,
                method: "GET",
                success: function (response) {
                    if (response.status == 'success') {
                        console.log(response);
                        const ctx = document.getElementById('postsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: response.labels,
                                datasets: response.datasets
                            },
                            options: {
                                responsive: true,
                                interaction: {
                                    mode: 'index',
                                    intersect: false,
                                },
                                scales: {
                                    x: {
                                        title: {
                                            display: true,
                                        }
                                    },
                                    y: {
                                        beginAtZero: true,
                                    }
                                }
                            }
                        })
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
        });
    </script>


@endsection