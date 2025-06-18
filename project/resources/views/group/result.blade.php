@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/group.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Các nhóm cộng đồng</h2>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="group-card" style="background-color:#fff;">
                            <div class="group-banner"
                                style="background-image: url('{{ asset('storage/' . $group->bgr) }}'); background-size: cover; background-position: center; height: 180px; position: relative; border-radius: 10px;">
                                <div class="overlay"
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.4); border-radius: 10px;">
                                </div>
                                <h4 class="group-name"
                                    style="position: absolute; bottom: 10px; left: 15px; color: white; z-index: 2;">
                                    {{ $group->name }}
                                </h4>
                            </div>
                            <div class="group-content mt-2 p-2">
                                <p class="description">{{ $group->description }}</p>

                                <div class="meta d-flex align-items-center gap-2">

                                    @if ($invite == true)
                                        <button id="accept" data-id="{{ $group->id }}"
                                            class="btn btn-success btn-sm float-end">Đồng
                                            ý
                                        </button>
                                    @else
                                        <form id="join">
                                            @csrf
                                            <input type="hidden" id="group_id" value={{ $group->id }}>
                                            <button type="submit" class="btn btn-outline-primary btn-sm float-end">Xin vào
                                            </button>
                                        </form>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <script>
        const user = localStorage.getItem('user_id');
        const joinForm = document.getElementById('join');
        if (joinForm) {
            joinForm.addEventListener('submit', function (e) {
                e.preventDefault();
                let id = document.getElementById('group_id').value;
                let auth_token = localStorage.getItem('auth_token');

                $.ajax({
                    url: `/api/group/find-result/join/${user}/${id}`,
                    method: "POST",
                    headers: {
                        'x-csrf-token': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${auth_token}`
                    },
                    success: function (response) {
                        if (response.success == true) {
                            alert(response.message);
                        } else {
                            alert('Không tìm thấy nhóm!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);
                    }
                });
            });
        }
        const acceptInviteBtn = document.getElementById('accept');
        if (acceptInviteBtn) {
            acceptInviteBtn.addEventListener('click', function (e) {
                e.preventDefault();
                let group = acceptInviteBtn.dataset.id;
                let userId = localStorage.getItem('user_id');

                $.ajax({
                    url: `/group/accept-invite/${group}`,
                    method: "GET",
                    success: function (response) {
                        if (response.success == true) {

                            window.location.href = '/group/detail/' + userId + "/" + group;
                        } else {
                            alert('Không tìm thấy nhóm!');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error("Error: ", error);
                    }
                });
            });
        }
    </script>

@endsection