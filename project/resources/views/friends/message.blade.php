@extends('layout.blog')

@section('content')
    <div class="main-panel" style="width:100%;">
        <div class="content-wrapper" style="border-radius:25px;">
            <div class="container">
                <h3 class="text-center">
                    @if ($receiver)
                        Kết nối đến {{ $receiver->name }}
                    @else
                        Không tìm thấy người dùng!
                    @endif
                </h3>

                <div id="chat-box" style="border: 1px solid #ccc; padding: 10px; height: 400px; overflow-y: scroll;">
                    @if (!empty($messages) && $messages->count() > 0)
                        @foreach ($messages as $message)
                            @if ($message->sender_id == $sender->id)
                                <div style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
                                    <div
                                        style="max-width: 60%; background:#e0f7fa; padding: 10px; border-radius: 10px; text-align: right;">
                                        <strong>{{ $message->sender->name }}</strong>
                                        <p style="margin: 0;">{{ $message->content }}</p>
                                        <small style="color: #aaa;">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <img src="{{ asset('storage/' . $message->sender->avatar) }}" alt="Avatar"
                                        style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                                </div>
                            @else
                                <div style="margin-bottom: 15px; display: flex; justify-content: flex-start;">
                                    <img src="{{ asset('storage/' . $message->sender->avatar) }}" alt="Avatar"
                                        style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                                    <div style="max-width: 60%; background:#fff3e0; padding: 10px; border-radius: 10px;">
                                        <strong>{{ $message->sender->name }}</strong>
                                        <p style="margin: 0;">{{ $message->content }}</p>
                                        <small style="color: #aaa;">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-center text-muted">Chưa có tin nhắn nào!</p>
                    @endif
                </div>


                <form id="chat" style="margin-top: 20px;">
                    @csrf
                    <input type="hidden" id="receiver_id" value="{{ $receiver->id }}">
                    <textarea id="message" name="message" rows="3" style="width: 100%;" placeholder="..."
                        required></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Gửi</button>
                </form>
            </div>
        </div>
    </div>


    <script>
        window.addEventListener('DOMContentLoaded', () => {
            const chatBox = document.getElementById("chat-box");
            chatBox.scrollTop = chatBox.scrollHeight;
        });
        receiverId = parseInt(document.getElementById('receiver_id').value);
        const senderId = parseInt(localStorage.getItem('user_id'));
        let id1 = Math.min(senderId, receiverId);
        let id2 = Math.max(senderId, receiverId);
        window.Echo.private(`chat-channel.${id1}.${id2}`)
            .listen('.message.sent', (e) => {
                const chatBox = document.getElementById("chat-box");
                const avatarUrl = `/storage/${e.sender.avatar}`;
                const isSender = (e.sender.id == senderId);
                let messHTML = '';
                if (isSender) {
                    messHTML = `<div style="margin-bottom: 15px; display: flex; justify-content: flex-end;">
                                                                                                                                                            <div style="max-width: 60%; background:#e0f7fa; padding: 10px; border-radius: 10px; text-align: right;">
                                                                                                                                                                <strong>${e.sender.name}</strong>
                                                                                                                                                                <p style="margin: 0;">${e.content}</p>
                                                                                                                                                                <small style="color: #aaa;">${new Date(e.created_at).toLocaleString()}</small>
                                                                                                                                                            </div>
                                                                                                                                                            <img src="${avatarUrl}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-left: 10px;">
                                                                                                                                                        </div>`;
                } else {

                    messHTML = `<div style="margin-bottom: 15px; display: flex; justify-content: flex-start;">
                                                                                                                                                            <img src="${avatarUrl}" alt="Avatar" style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                                                                                                                                                            <div style="max-width: 60%; background:#fff3e0; padding: 10px; border-radius: 10px;">
                                                                                                                                                                <strong>${e.sender.name}</strong>
                                                                                                                                                                <p style="margin: 0;">${e.content}</p>
                                                                                                                                                                <small style="color: #aaa;">${new Date(e.created_at).toLocaleString()}</small>
                                                                                                                                                            </div>
                                                                                                                                                        </div>`;
                }
                chatBox.innerHTML += messHTML;
                chatBox.scrollTop = chatBox.scrollHeight;
            });

        document.getElementById('chat').addEventListener('submit', function (e) {
            e.preventDefault();
            const receiverId = parseInt(document.getElementById('receiver_id').value);
            const senderId = parseInt(localStorage.getItem('user_id'));
            const authToken = localStorage.getItem('auth_token');
            const content = document.getElementById('message').value;
            fetch('/send-message/' + senderId + '/' + receiverId, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": '{{ csrf_token() }}',
                    "Content-Type": "application/json",
                    'Authorization': `Bearer ${authToken}`
                },
                body: JSON.stringify({
                    content: content
                })
            }).then(response => response.json())
                .then(data => {
                    if (data.success == true) {
                        const chatBox = document.getElementById("chat-box");
                        const sender = data.sender;
                        const content = data.message.content;
                        const createdAt = new Date(data.message.created_at).toLocaleString();
                        const avatarUrl = `/storage/${sender.avatar}`;
                        document.getElementById('message').value = '';
                    }
                    else {
                        alert(data.message);
                    }
                }).catch(error => console.error("Error:", error));
        });
    </script>


@endsection