@extends('layout.blog')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/search-user-page.css') }}">
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="user-profile-container">
                <div class="user-card">
                    <div class="avatar">
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="User Avatar">
                    </div>
                    @if ($user)
                        <div class="user-info">
                            <a href="{{ route('friend.page', ['auth' => $auth->id, 'user' => $user->id]) }}">
                                <h2 class="user-name">{{ $user->name }}</h2>
                            </a>
                            <p class="user-email">{{ $user->email }}</p>
                            <p class="user-id"><strong>ID:</strong> {{ $user->id }}</p>
                        </div>
                    @else
                        <div class="user-info">
                            N/A
                        </div>
                    @endif
                    <div class="action-buttons">
                        <form id="follow-action">
                            @csrf
                            <input id="friendId" type="hidden" value="{{ $user->id }}">
                            @php
                                $friend = $auth
                                    ->friends()
                                    ->where('friend_id', $user->id)
                                    ->orWhere('user_id', $user->id)
                                    ->first();
                            @endphp
                            @if ($friend)
                                <button style="background-color:blue;" class="btn-add-friend">
                                    @if (auth()->user()->isBlockedBy($user->id))
                                        <span>Bị chặn</span>
                                    @elseif ($friend->pivot->status == 'declined')
                                        <span>Đang chặn</span>
                                    @elseif ($friend->pivot->status == 'pending')
                                        <span>Đã gửi yêu cầu</span>
                                    @elseif ($friend->pivot->status == 'accepted')
                                        <span>Bạn bè</span>
                                    @endif



                                </button>
                            @else
                                <button id="friend-request-btn" class="btn-add-friend">Kết bạn</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        document.getElementById("follow-action").addEventListener("submit", function (e) {
            e.preventDefault();
            const friendId = document.getElementById('friendId').value;
            let auth_token = localStorage.getItem('auth_token');
            fetch('/api/nofication/follow-user', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}',
                    "Content-Type": "application/json",
                    'Authorization': `Bearer ${auth_token}`
                },
                body: JSON.stringify({
                    friendId: friendId
                })
            }).then(response => response.json()).then(data => {
                if (data.success == true) {
                    const followButton = document.getElementById('friend-request-btn');
                    followButton.innerText = 'Đã gửi yêu cầu';
                    followButton.style.backgroundColor = '#4B49AC';
                    followButton.style.color = 'white';
                } else {
                    alert(data.message);
                }
            }).catch(error => console.error("Error:", error));
        })
    </script>
@endsection