@extends('layout.blog')

@section('content')
    <link rel="stylesheet" href="{{ asset('assets/css/friendlist.css') }}">

    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <div class="d-flex flex-wrap friend-container justify-content-start gap-3"
                    style="max-height:100vh; overflow-y: auto;">
                    @if(!empty($friends))
                        @foreach($friends as $f)
                            @php
                                $user = $f->friend->id == $id ? $f->user : $f->friend;
                            @endphp
                            <div class="card friend-card me-2 mb-3">
                                <img src="{{ asset('storage/' . $user->avatar) }}" class="card-img-top" alt="Avatar">
                                <div class="card-body text-center p-2">
                                    <a href="{{route('friend.page', ['auth' => auth()->id(), 'user' => $user->id])}}">
                                        <h5 class="user-name">{{ $user->name }}</h5>
                                    </a>
                                    <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle mb-2" alt="Avatar">
                                    <form class="sendMessage">
                                        @csrf
                                        <input type="hidden" class="receiverId" value="{{$user->id}}">
                                        <button type="submit" class="btn btn-primary btn-message">Gửi tin nhắn</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center">Không có bạn bè nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const forms = document.querySelectorAll('.sendMessage');
            forms.forEach(function (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const senderId = localStorage.getItem('user_id');
                    const authToken = localStorage.getItem('auth_token');
                    const receiverId = this.querySelector('.receiverId').value;

                    window.location.href = "/chat/" + senderId + "/" + receiverId;
                })
            })


        })
    </script>
@endsection