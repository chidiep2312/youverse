@extends('admin.layout')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container py-4">
                <h2 class="mb-4">Dashboard</h2>
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Tổng số người dùng</h6>
                                <div class="fs-4 fw-bold"><span id="userCount">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Tổng nhóm người dùng</h6>
                                <div class="fs-4 fw-bold"><span id="groupCount">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Tổng số bài viết</h6>
                                <div class="fs-4 fw-bold"><span id="postCount">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Số lượng bài viết ngắn</h6>
                                <div class="fs-4 fw-bold"><span id="threadCount">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Tổng số bài vi phạm</h6>
                                <div class="fs-4 fw-bold"><span id="violationPostCount">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Số bài viết bị report</h6>
                                <div class="fs-4 fw-bold"><span id="reports">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Report đang chờ xử lý</h6>
                                <div class="fs-4 fw-bold"><span id="pending">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-bg-light">
                            <div class="card-body">
                                <h6>Thông báo admin</h6>
                                <div class="fs-4 fw-bold"><span id="announce">Đang tính...</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Người dùng mới theo tháng</h6>
                        <canvas id="usersChart" style="width:100%;height:400px"></canvas>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Số lượng bài viết theo tháng</h6>
                        <canvas id="postsChart" style="width:100%;height:400px"></canvas>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Top bài viết được xem nhiều nhất</h6>
                                <ol>
                                    @if(isset($mostViewPosts))
                                        @foreach($mostViewPosts as $p)
                                            <li style="color:rgb(117, 117, 202)"><strong>{{ $p->title }}</strong> -
                                                {{ $p->like_count }} views
                                            </li>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu</p>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Top bài viết được like nhiều nhất</h6>
                                <ol>
                                    @if(isset($mostLikePosts))
                                        @foreach($mostLikePosts as $p)
                                            <li style="color:rgb(117, 117, 202)"><strong>{{ $p->title }}</strong> -
                                                {{ $p->like_count }} views
                                            </li>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu</p>
                                    @endif
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-body">
                        <h6>Số lượng report theo tháng</h6>
                        <canvas id="reportsChart" style="width:100%;height:400px"></canvas>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Lý do report phổ biến</h6>
                                <ul>
                                    @if(isset($topReportedReasons))
                                        @foreach($topReportedReasons as $p)
                                            <li style="color:rgb(117, 117, 202)"><strong>{{ $p->reason }}</strong> -
                                                {{ $p->reason_count }} views
                                            </li>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu</p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h6>Bài viết bị report nhiều nhất</h6>
                                <ul>
                                    @if(isset($topReportedPosts))
                                        @foreach($topReportedPosts as $p)
                                            <li style="color:rgb(117, 117, 202)"><strong>{{ $p['title'] }}</strong> -
                                                {{ $p['report_count']}} views</li>
                                        @endforeach
                                    @else
                                        <p>Không có dữ liệu</p>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            let posts = document.getElementById('postCount');
            let violationPosts = document.getElementById('violationPostCount');
            let users = document.getElementById('userCount');
            let groups = document.getElementById('groupCount');
            let threads = document.getElementById('threadCount');
            let announce = document.getElementById('announce');
            let reports = document.getElementById('reports');
            let pending = document.getElementById('pending');
            $.ajax({
                url: `/admin/statistic`,
                method: "GET",
                success: function (response) {

                    if (response.success == true) {
                        posts.textContent = response.data.posts;
                        users.textContent = response.data.users;
                        threads.textContent = response.data.threads;
                        announce.textContent = response.data.active + "/" + response.data.announce;
                        groups.textContent = response.data.groups;
                        pending.textContent = response.data.pending;
                        reports.textContent = response.data.reports;
                        violationPosts.textContent = response.data.violationPosts;
                    } else {
                        posts.textContent = 'Lỗi';
                        users.textContent = 'Lỗi';
                        threads.textContent = 'Lỗi';
                        announce.textContent = 'Lỗi';
                        groups.textContent = 'Lỗi';
                        pending.textContent = 'Lỗi';
                        reports.textContent = 'Lỗi';
                        violationPosts.textContent = 'Lỗi';
                    }
                },
                error: function (xhr, status, error) {
                    console.error("Error:", error);
                }
            });
            $.ajax({
                url: `/admin/chart/new-users`,
                method: "GET",
                success: function (response) {
                    if (response.status == 'success') {

                        const ctx = document.getElementById('usersChart').getContext('2d');
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

            $.ajax({
                url: `/admin/chart/new-posts`,
                method: "GET",
                success: function (response) {
                    if (response.status == 'success') {

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
            $.ajax({
                url: `/admin/chart/reports`,
                method: "GET",
                success: function (response) {
                    if (response.status == 'success') {

                        const ctx = document.getElementById('reportsChart').getContext('2d');
                        new Chart(ctx, {
                            type: 'bar',
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