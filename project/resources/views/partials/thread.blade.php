<div class="short-post shadow-sm rounded-4 p-4 mb-4 bg-white">
    <div class="main-content pb-3">
        <div class="d-flex align-items-center mb-3">
            <img src="{{ asset('storage/' . $thread->user->avatar) }}" class="rounded-circle shadow-sm" width="48"
                height="48" style="object-fit: cover; border: 2px solid #fff;">
            <div class="ms-3">
                <div class="fw-semibold"><a href="{{ route('friend.page', ['user' => $thread->user->id]) }}">
                        <h5 style="color:black">{{ $thread->user->name }}</h5>
                    </a>
                </div>
                <small class="text-muted">{{ $thread->created_at->format('d M Y') }}</small>
            </div>
        </div>
        <div class="thread-content mb-2">{!! $thread->content !!}</div>
    </div>

    <div class="d-flex justify-content-end">
        <a class="btn" href="{{route('thread.detail', ['thread' => $thread->id]) }}">
            <i class="fa-solid fa-circle-info"></i>
        </a>
        <button class="btn btn-sm btn-light border toggle-comments-btn" data-thread-id="{{ $thread->id }}">
            <i class="fa-solid fa-comment me-1"></i> Bình luận
        </button>
    </div>

    <div class="comment-container mt-3" style="padding:0;" id="comments_section">
        <div class="comments-section" id="comments-{{ $thread->id }}">
            @include('partials.thread-comment', ['thread' => $thread])
            @include('partials.comment-form', ['thread' => $thread])
        </div>
    </div>
</div>

<style>
    .short-post {
        border-radius: 25px;
        transition: box-shadow 0.2s ease-in-out;
    }

    .short-post:hover {
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }

    .main-content {
        border-bottom: 1px dashed #a7caf8;
    }

    .thread-content {
        font-size: 0.95rem;
        white-space: pre-wrap;
        word-break: break-word;
    }

    .comment-container {
        background-color: #f8f9fb;
        padding: 1rem;
        border-radius: 0.75rem;
    }

    img.rounded-circle {
        object-fit: cover;
    }
</style>